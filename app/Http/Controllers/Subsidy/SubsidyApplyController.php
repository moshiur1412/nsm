<?php

namespace App\Http\Controllers\Subsidy;
use Auth;
use Mail;
use Storage;
use Carbon\Carbon;
use App\Models\Topic;
use App\Models\Keyword;
use App\Models\Award;
use App\Models\Subsidy;
use App\Models\SubsidyAction;
use App\Models\CustomTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\Kana;
use App\Rules\NoSymbol;
use App\Rules\Romaji;
use App\Rules\SpaceIn;
use App\Events\SubsidyAppCompleted;

class SubsidyApplyController extends Controller
{
	protected $topic;
	protected $subsidy;
	protected $keyword;
	protected $custom_topic;

	function __construct(Subsidy $subsidy, Topic $topic, Keyword $keyword, CustomTopic $custom_topic, SubsidyAction $subsidy_actions)
	{
		$this->middleware('auth');
		$this->topic = $topic;
		$this->subsidy = $subsidy;
		$this->keyword = $keyword;
		$this->custom_topic = $custom_topic;
		$this->subsidy_actions = $subsidy_actions;
	}

	public function create(Request $request)
	{

		if(\Gate::denies('apply-access')){
			return abort(403, "Access Denied");
		}

		$states = config('states');
		$custom_categories = $this->custom_topic->get();
		$categories = $this->topic->where('valid', 1)->get();

		$applicant = $auth_user = Auth::user();

		$prev_input = session('prev_subsidy_input'); 
		session()->forget('prev_subsidy_input');

		return view('subsidy.subsidyApply.create', compact('states', 'categories', 'custom_categories','applicant', 'prev_input'));

	}

	public function confirm(Request $request)
	{
		$this->validate($request, [
			'name' => ['required','string',new NoSymbol($request->all()),new SpaceIn($request->all())],
			'name_kana' => ['required','string',new Kana($request->all()),new NoSymbol($request->all()),new SpaceIn($request->all())],
			'name_alphabet' => ['required','string',new Romaji($request->all()),new NoSymbol($request->all()),new SpaceIn($request->all())],
			'birthday' => 'required|after_or_equal:'.Carbon::parse(config('expirationdate'))->format('Y-m-d'),
			'belongs' => 'required|string|min:2|max:255',
			'belong_type_name' => 'required|string|min:2|max:255',
			'major' => 'required|string|min:2|max:255',
			'occupation' => 'required|string|min:2|max:255',
			'other_occupation'=>'required_if:occupation,"その他"',
			'zip_code' => 'required|string|min:2|max:255',
			'address1' => 'required|max:1000',
			'address2' => 'max:1000',
			'theme' => 'required|string|max:40',
			'topic' => 'numeric',
			'application' => 'required|mimes:pdf|max:5000',
			'attachment' => 'required|mimes:pdf|max:5000',
			'reference' => 'required|mimes:pdf|max:5000',
		], [
			'birthday.after_or_equal'  => ':attributeが:date以前の方は応募対象外となります。',
    ]);

		$auth_user = Auth::user();
		$subsidy = $this->subsidy;
		$data = $request->all();

		if(!empty($data['other_occupation']) && $data['occupation'] == 'その他'){
			$data['occupation'] = $data['other_occupation'];
		}

		$keyword = $this->keyword->findOrFail($request->topic);

		$data['topic'] = json_encode([
			'keyword' => array('id' => $keyword->id, 'name' => $keyword->name, 'prefix' => $keyword->prefix),
			'topic' => array('id' => $keyword->topic->id, 'name' => $keyword->topic->name, 'description' => $keyword->topic->description)
		]);

		$tmp_folder = md5(uniqid(rand(), true)).'-'.strtoupper($data['name_alphabet']);

		$data['application'] = $request->file('application')->getClientOriginalName();
		$request->file('application')->storeAs($tmp_folder.'/', 'application.pdf','tmp');
		$data['application_path'] = asset('storage/tmp/'.$tmp_folder.'/application.pdf');;

		$data['attachment'] = $request->file('attachment')->getClientOriginalName();
		$request->file('attachment')->storeAs($tmp_folder.'/', 'attachment.pdf','tmp');
		$data['attachment_path'] = asset('storage/tmp/'.$tmp_folder.'/attachment.pdf');

		$data['reference'] = $request->file('reference')->getClientOriginalName();
		$request->file('reference')->storeAs($tmp_folder.'/', 'reference.pdf','tmp');
		$data['reference_path'] = asset('storage/tmp/'.$tmp_folder.'/reference.pdf');

		return view('subsidy.subsidyApply.confirm', compact('data', 'tmp_folder', 'auth_user', 'subsidy'));
	}

	public function cancel(Request $request)
	{
		try {
			$data = array();
			parse_str($request->datastring, $data);
			$request->session()->put('prev_subsidy_input', $data);
			Storage::disk('tmp')->deleteDirectory($data['tmp_folder']);
			return ['status'=>200,'reason'=>'','success' => 'tmp folder deleted!'];
		}
		catch (\Exception $e) {
			return ['status'=>401, 'reason'=>$e->getMessage()];
		}
	}

	public function store(Request $request)
	{
			$input = $request->all();

			$input['state'] = 2;
			$input['birthday'] = Carbon::parse($input['birthday'])->format('Y-m-d');

			$input['application_path'] = str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_1.pdf';
			$input['attachment_path'] = str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_2.pdf';
			$input['merged_path'] = str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_3.pdf';
			$input['reference_path'] = str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_4.pdf';
			$input['expiration_date'] = date("Y-10-31");
			$input['offer_year'] = date("Y");
			$this->subsidy = $this->subsidy->create($input);
			$id = $this->subsidy->id;
			$receipt = $this->subsidy->receipt;
			$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '新規登録をしました']);
		try {
			$app = Storage::disk('tmp')->get($input['tmp_folder']."/".'application.pdf');
			$att = Storage::disk('tmp')->get($input['tmp_folder']."/".'attachment.pdf');
			$ref = Storage::disk('tmp')->get($input['tmp_folder']."/".'reference.pdf');
			Storage::disk('noguchi')->put(date('Y').'/subsidy/'.$receipt.'_'.$input['application_path'], $app);
			Storage::disk('noguchi')->put(date('Y').'/subsidy/'.$receipt.'_'.$input['attachment_path'], $att);
			$this->storeMerged(date('Y').'/subsidy/'.$receipt.'_'.$input['application_path'], date('Y').'/subsidy/'.$receipt.'_'.$input['attachment_path'], date('Y').'/subsidy/'.$receipt.'_'.$input['merged_path']);
			Storage::disk('noguchi')->put(date('Y').'/subsidy/'.$receipt.'_'.$input['reference_path'], $ref);

			$subsidy = $this->subsidy->findOrFail($id);
			$input['application_path'] = $receipt."_".$input['application_path'] ;
			$input['attachment_path'] = $receipt."_".$input['attachment_path'];
			$input['merged_path'] = $receipt."_".$input['merged_path'];
			$input['reference_path'] = $receipt."_".$input['reference_path'];
			$subsidy->update($input);
			event(new SubsidyAppCompleted($request));
		} catch (\Swift_IoException $se) {
			$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '申請完了メールの送信に失敗しました。']);
			//TODO: redirect w/ error message
			return redirect()->back()->with('error', ['失敗' => '申請完了メールの送信に失敗しました。']);
		} catch (\Exception $e) {
			$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => 'ファイルの新規アップロードに失敗しました']);
			$subsidy = $this->subsidy->findOrFail($id);
			$subsidy->delete();
			//TODO: redirect w/ error message
			throw $e;
		} finally {
			Storage::disk('tmp')->deleteDirectory($input['tmp_folder']);
		}
		return view('subsidy.subsidyApply.store');

	}

	public function edit($id)
	{
		$subsidy = Subsidy::findOrFail($id);
		$states = config('states');
		$custom_categories = $this->custom_topic->get();
		$categories = $this->topic->where('valid', 1)->get();

		if ($subsidy->state > 2) {
			$this->show($id);
			return;
		}

		$prev_input = session('prev_subsidy_input'); 
		session()->forget('prev_subsidy_input');

		return view ('subsidy.subsidyApply.edit', compact('subsidy', 'states', 'categories', 'custom_categories', 'prev_input'));
	}

	public function editConfirm(Request $request, $id)
	{
		$this->validate($request, [
			'name' => ['required','string',new NoSymbol($request->all()),new SpaceIn($request->all())],
			'name_kana' => ['required','string',new Kana($request->all()),new NoSymbol($request->all()),new SpaceIn($request->all())],
			'name_alphabet' => ['required','string',new Romaji($request->all()),new NoSymbol($request->all()),new SpaceIn($request->all())],
			'birthday' => 'required|after_or_equal:'.Carbon::parse(config('expirationdate'))->format('Y-m-d'),
			'belongs' => 'required|string|min:2|max:255',
			'belong_type_name' => 'required|string|min:2|max:255',
			'major' => 'required|string|min:2|max:255',
			'occupation' => 'required|string|min:2|max:255',
			'other_occupation'=>'required_if:occupation,"その他"',
			'zip_code' => 'required|string|min:2|max:255',
			'address1' => 'required|max:1000',
			'address2' => 'max:1000',
			'theme' => 'required|string|max:40',
			'topic' => 'numeric',
			'application' => 'mimes:pdf|max:5000|required_with:attachment',
			'attachment' => 'mimes:pdf|max:5000|required_with:application',
			'reference' => 'mimes:pdf|max:5000',
		], [
			'birthday.after_or_equal'  => ':attributeが:date以前の方は応募対象外となります。',
    ]);

		$data = $request->all();
		$subsidy = $this->subsidy->findOrFail($id);

		if(!empty($data['other_occupation']) && $data['occupation'] == 'その他'){
			$data['occupation'] = $data['other_occupation'];
		}

		$keyword = $this->keyword->findOrFail($request->topic);

		$data['topic'] = json_encode([
			'keyword' => array('id' => $keyword->id, 'name' => $keyword->name, 'prefix' => $keyword->prefix),
			'topic' => array('id' => $keyword->topic->id, 'name' => $keyword->topic->name, 'description' => $keyword->topic->description)
		]);

		$tmp_folder = md5(uniqid(rand(), true)).'-'.strtoupper($data['name_alphabet']);
		$downloadUrl = config('url.pdfDownload').date('Y', strtotime($subsidy->created_at)).'/subsidy/';

		$data['application'] = "";
		$data['application_path'] = "";
		$data['attachment'] = "";
		$data['attachment_path'] = "";
		$data['reference'] = "";
		$data['reference_path'] = "";
		if($request->hasFile('application')){
			$data['application'] = $request->file('application')->getClientOriginalName();
			$request->file('application')->storeAs($tmp_folder.'/', 'application.pdf','tmp');
			$data['application_path'] = asset('storage/tmp/'.$tmp_folder.'/application.pdf');
		}

		if($request->hasFile('attachment')){
			$data['attachment'] = $request->file('attachment')->getClientOriginalName();
			$request->file('attachment')->storeAs($tmp_folder.'/', 'attachment.pdf','tmp');
			$data['attachment_path'] = asset('storage/tmp/'.$tmp_folder.'/attachment.pdf');
		}

		if($request->hasFile('reference')){
			$data['reference'] = $request->file('reference')->getClientOriginalName();
			$request->file('reference')->storeAs($tmp_folder.'/', 'reference.pdf','tmp');
			$data['reference_path'] = asset('storage/tmp/'.$tmp_folder.'/reference.pdf');
		}
		return view('subsidy.subsidyApply.confirm', compact('data', 'tmp_folder', 'subsidy'));
	}

	public function update(Request $request, $id)
	{
		$input = $request->all();
		$subsidy = $this->subsidy->findOrFail($id);

		$input['birthday'] = Carbon::parse($input['birthday'])->format('Y-m-d');

		try {
			$path = date('Y', strtotime($subsidy->created_at)).'/subsidy';
			$input['application_path'] = $subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_1.pdf';
			if (!empty($input['application'])){
				$request->file('application')->storeAs($path, $input['application_path'],'noguchi');
			}

			$input['attachment_path'] = $subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_2.pdf';
			if (!empty($input['attachment'])){
				$request->file('attachment')->storeAs($path, $input['attachment_path'],'noguchi');
			}

			$input['merged_path'] = $subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_3.pdf';
			if (!empty($input['application']) && !empty($input['attachment'])){
				$this->storeMerged($path.'/'.$input['application_path'], $path.'/'.$input['attachment_path'], $path.'/'.$input['merged_path']);
				$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '表紙・添付書類の結合ファイルを更新しました']);
			}

			$input['reference_path'] = $subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_4.pdf';
			if (!empty($input['reference'])){
				$request->file('reference')->storeAs($path, $input['reference_path'],'noguchi');
					$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '参考論文ファイルを更新しました']);
			}
			if($subsidy->name_alphabet != $input['name_alphabet']){
				$base_path = $path.'/';
				if (!empty($input['application']) && !empty($input['attachment'])){
	            // both name and file have been updated -> delete the old
					$this->deleteOld($base_path.$subsidy->application_path);
					$this->deleteOld($base_path.$subsidy->attachment_path);
					$this->deleteOld($base_path.$subsidy->merged_path);
					if (!empty($input['reference'])) {
						// reference also available
						$this->deleteOld($base_path.$subsidy->reference_path);
					}else{
						// reference not available
						$new_ref = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_4.pdf';
						$this->moveOld($base_path.$subsidy->reference_path, $new_ref);
					}
				} else if(!empty($input['reference'])) {
					//only reference is available
					$new_app = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_1.pdf';
					$new_att = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_2.pdf';
					$new_mrg = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_3.pdf';
					$this->moveOld($base_path.$subsidy->application_path, $new_app);
					$this->moveOld($base_path.$subsidy->attachment_path, $new_att);
					$this->moveOld($base_path.$subsidy->merged_path, $new_mrg);
					$this->deleteOld($base_path.$subsidy->reference_path);
				} else {
					$new_app = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_1.pdf';
					$new_att = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_2.pdf';
					$new_mrg = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_3.pdf';
					$new_ref = $base_path.$subsidy->receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_4.pdf';
	            // only name has been updated -> rename the old to new
					$this->moveOld($base_path.$subsidy->application_path, $new_app);
					$this->moveOld($base_path.$subsidy->attachment_path, $new_att);
					$this->moveOld($base_path.$subsidy->merged_path, $new_mrg);
					$this->moveOld($base_path.$subsidy->reference_path, $new_ref);
					$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '氏名（ローマ字）が更新されたのでファイル名を更新しました']);
				}
			}
			$subsidy->update($input);
			$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '応募内容を更新しました']);
		} catch (\Exception $e) {
			$subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => 'ファイルの再アップロードに失敗しました']);
			throw $e;
		}
		return redirect()->route('subsidyApply.show', $id)->with('success', ['成功' => '応募内容が更新されました']);
	}


	public function show($id)
	{
		$subsidy = $this->subsidy->where('user_id', Auth::user()->id)->first();
		if(!empty($subsidy)){
			$downloadUrl = config('url.pdfDownload').date('Y', strtotime($subsidy->created_at)).'/subsidy/';
			$downloadUrlList = [];
			$downloadUrlList[0] = $downloadUrl.$subsidy->application_path;
			$downloadUrlList[1] = $downloadUrl.$subsidy->attachment_path;
			$downloadUrlList[2] = $downloadUrl.$subsidy->merged_path;
			$downloadUrlList[3] = $downloadUrl.$subsidy->reference_path;
			return view ('subsidy.subsidyApply.show', compact('subsidy', 'downloadUrlList'));
		}
		return abort('404');
	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$subsidy = $this->subsidy->findOrFail($id);
    	$path = date('Y', strtotime($subsidy->created_at)).'/subsidy';
    	$base_path = $path.'/';
    	$this->deleteOld($base_path.$subsidy->application_path);
    	$this->deleteOld($base_path.$subsidy->attachment_path);
    	$this->deleteOld($base_path.$subsidy->merged_path);
    	$this->deleteOld($base_path.$subsidy->reference_path);
    	$subsidy->delete();
		  $subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '応募を削除しました']);
    	return redirect()->route('mypage.index')->with('success', ['成功' => '応募は削除されました']);
    }
}
