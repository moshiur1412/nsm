<?php

namespace App\Http\Controllers\Subsidy;

use Auth;
use Storage;
use Carbon\Carbon;
use App\Models\Award;
use App\Models\Subsidy;
use App\Models\Applicant;
use App\Models\AwardAction;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmAwardRequest;
use App\Http\Requests\SubmissionAwardRequest;
use App\Http\Requests\UpdateAwardApplyRequest;
use App\Events\AwardAppCompleted;

class AwardApplyController extends Controller
{
	protected $award;
	protected $subsidy;
	protected $applicant;

	public function __construct(Award $award, Applicant $applicant, Subsidy $subsidy, AwardAction $award_acton)
	{
		$this->middleware('auth');
		$this->award = $award;
		$this->subsidy = $subsidy;
		$this->applicant = $applicant;
		$this->award_action = $award_acton;
	}

	public function create()
	{
		\Log::info(url()->previous());
		if(\Gate::denies('apply-access')){
			return abort(403, "Access Denied");
		}
		$applicant = $auth_user = Auth::user();

		$prev_input = session('prev_award_input'); 
		session()->forget('prev_award_input');

		return view('subsidy.awardApply.create',compact('applicant', 'prev_input'));

	}

	public function confirm(ConfirmAwardRequest $request)
	{

		$award = $this->award;
		$data = $request->all();

		if ($request->job == "その他") {
			$data['occupation'] = $request->job_other;
		}else {
			$data['occupation'] = $request->job;
		}

		$data['zip_code'] = $request->postalcode_1.'-'.$request->postalcode_2;
		$data['attachment'] = $request->file('attachment')->getClientOriginalName();

		$tmp_folder = md5(uniqid(rand(), true)).'-'.strtoupper($data['name_alphabet']);
		$request->file('attachment')->storeAs($tmp_folder.'/', 'attachment.pdf','tmp');
		$data['attachment_path'] = asset('storage/tmp/'.$tmp_folder.'/attachment.pdf');

		return view('subsidy.awardApply.confirm', compact('data', 'tmp_folder', 'award'));
	}


	public function cancel(Request $request)
	{
		try {
			$data = array();
			parse_str($request->datastring, $data);
			$request->session()->put('prev_award_input', $data);
			Storage::disk('tmp')->deleteDirectory($data['tmp_folder']);
			return ['status'=>200,'reason'=>'','success' => 'tmp folder deleted!'];
		}
		catch (\Exception $e) {
			return ['status'=>401, 'reason'=>$e->getMessage()];
		}
	}


	public function store(Request $request)
	{
		$id = null;
		$input = $request->all();

		$input['user_id'] = Auth::user()->id;
		$subsidy = $this->subsidy->where('offer_year', $request->subsidy_granted_year)->where('name_alphabet', $request->name_alphabet)->where('is_granted', 1)->first();
		if($subsidy){
			$input['subsidy_id'] = $subsidy->id;
		}
		$input['birthday'] = Carbon::parse($request->birthday)->format('Y-m-d');
		$input['expiration_date'] = date("Y-10-31");
		$input['offer_year'] = date("Y");
		$input['attachment_path'] = '';
		$this->award = $this->award->create($input);
		$id = $this->award->id;
		$receipt = $this->award->receipt;
		$award_actions = AwardAction::create(['award_id' => $id ,'action' => '新規登録をしました']);
		try {
			$input['attachment_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'.pdf';
			$app = Storage::disk('tmp')->get($input['tmp_folder'].'/attachment.pdf');
			Storage::disk('noguchi')->put(date('Y').'/award/'.$input['attachment_path'], $app);

			$award = $this->award->findOrFail($id);
			$award->update($input);
			Storage::disk('tmp')->deleteDirectory($request->tmp_folder);
			event(new AwardAppCompleted($request));
		} catch (\Swift_IoException $se) {
			$award_actions = AwardAction::create(['award_id' => $id ,'action' => '申請完了メールの送信に失敗しました。']);
			//TODO: redirect w/ error message
			return redirect()->back()->with('error', ['失敗' => '申請完了メールの送信に失敗しました。']);
		} catch (\Exception $e) {
			$award_actions = AwardAction::create(['award_id' => $id ,'action' => 'ファイルの新規アップロードに失敗しました']);
			if (!is_null($id)) {
				$award = $this->award->findOrFail($id);
				$award->delete();
			}
			throw $e;
		} finally {
			Storage::disk('tmp')->deleteDirectory($input['tmp_folder']);
		}
		return view('subsidy.awardApply.store');
	}

	public function edit($id)
	{
		$award = Award::FindOrFail($id);
		if ($award->state > 2) {
			$this->show($id);
			return;
		}

		$prev_input = session('prev_award_input'); 
		session()->forget('prev_award_input');

		return view('subsidy.awardApply.edit', compact('award', 'prev_input'));
	}

	public function editConfirm(UpdateAwardApplyRequest $request, $id)
	{
		$data = $request->all();
		$award = $this->award->findOrFail($id);

		if ($request->occupation == "その他") {
			$data['occupation'] = $request->job_other;
		}else {
			$data['occupation'] = $request->occupation;
		}

		$data['zip_code'] = $request->postalcode_1.'-'.$request->postalcode_2;

		$tmp_folder = md5(uniqid(rand(), true)).'-'.strtoupper($data['name_alphabet']);
		$downloadUrl = config('url.pdfDownload').date('Y', strtotime($award->created_at)).'/award/';

		$data['attachment'] = "";
		$data['attachment_path'] = "";

		if($request->hasFile('attachment')){
			$data['attachment'] = $request->file('attachment')->getClientOriginalName();
			$request->file('attachment')->storeAs($tmp_folder.'/', 'attachment.pdf','tmp');
			$data['attachment_path'] = asset('storage/tmp/'.$tmp_folder.'/attachment.pdf');
		}

		return view('subsidy.awardApply.confirm', compact('data', 'tmp_folder', 'award'));
	}

	public function update(Request $request, $id)
	{
		$award = $this->award::findOrFail($id);

		$input = $request->all();

		$input['birthday'] = Carbon::parse($input['birthday'])->format('Y-m-d');

		try {
			$path = date('Y', strtotime($award->created_at)).'/award';
			$input['attachment_path'] = $award->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'.pdf';
			if (!empty($input['attachment'])){
				$request->file('attachment')->storeAs($path, $input['attachment_path'],'noguchi');
			}

			if($award->name_alphabet != $input['name_alphabet']){
				$base_path = $path.'/';
				if (!empty($input['attachment'])){
					$this->deleteOld($base_path.$award->attachment_path);
					$award_actions = AwardAction::create(['award_id' => $id ,'action' => '申請書ファイルを更新しました']);
				} else {
					$new_app = $base_path.$award->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'.pdf';
					$this->moveOld($base_path.$award->attachment_path, $new_app);
					$award_actions = AwardAction::create(['award_id' => $id ,'action' => '氏名（ローマ字）が更新されたのでファイル名を更新しました']);
				}
			}
			$award->update($input);
			$award_actions = AwardAction::create(['award_id' => $id ,'action' => '応募内容を更新しました']);
		} catch (\Exception $e) {
			$award_actions = AwardAction::create(['award_id' => $id ,'action' => 'ファイルの再アップロードに失敗しました']);
			throw $e;
		}

		return redirect(route('awardApply.show', Auth::user()->id))->with("success", ["成功"=> "応募は更新されました。"]);
	}

	public function show($id)
	{
		$award = $this->award->where('user_id', Auth::user()->id)->first();
		if(!empty($award)){
			$downloadUrl = config('url.pdfDownload').date('Y', strtotime($award->created_at)).'/award/';
			$downloadUrlList = $downloadUrl.$award->attachment_path;
			return view ('subsidy.awardApply.show', compact('award', 'downloadUrlList'));
		}
		return abort('404');
	}

	public function destroy($id)
	{
		$award = $this->award->findOrFail($id);
		$path = date('Y', strtotime($award->created_at)).'/award';
		$base_path = $path.'/';
		$this->deleteOld($base_path.$award->attachment_path);
		$award->delete();
		$award_actions = AwardAction::create(['award_id' => $id ,'action' => '応募を削除しました']);
		return redirect(route("mypage.index"))->with("success", ["成功"=> "応募は削除されました。"]);
	}

}
