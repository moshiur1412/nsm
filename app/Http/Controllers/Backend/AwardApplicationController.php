<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Award;
use App\Models\Subsidy;
use App\Models\CustomTopic;
use App\Models\AwardAction;
use App\Models\Config;
use App\Models\Applicant;
use Auth;
use ZipArchive;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use App\Http\Requests\StoreAwardApplicationRequest;
use App\Http\Requests\UpdateAwardApplicationRequest;
use Illuminate\Support\Facades\Storage;
use App\Mail\ApplicantsMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Events\AwardRejectionNoticed;


class AwardApplicationController extends Controller
{
	protected $award;
	protected $custom_topic;

	public function __construct(Award $award, CustomTopic $custom_topic)
	{
		$this->award = $award;
		$this->custom_topic = $custom_topic;
		$this->middleware('auth:admin');
	}

	public function index()
	{
		$custom_categories = $this->custom_topic->get();
		$award_year = empty(\Request::get('award_year')) ?  date('Y') : \Request::get('award_year');
		$offer_years = $this->award->select('offer_year')->groupBy('offer_year')->orderBy('offer_year', 'desc')->where('valid',1)->get();
		$years = [];
		foreach ($offer_years as $obj) {
			$years[] = $obj->offer_year;
		}
		$awards = $this->award->orderBy('created_at', 'desc')->whereRaw('offer_year = ?', array($award_year))->get();

		$applicants = Applicant::all();
		$config = Config::first();
		$next_no = $config->next_award_receipt;
		return view('backend.awardApplication.index', compact('awards','custom_categories','award_year','years','applicants','next_no'));
	}


	public function create(Award $award)
	{
		return view('backend.awardApplication.create', compact('award'));
	}

	public function store(StoreAwardApplicationRequest $request)
	{
		$input = $request->all();

		if ($request->occupation == "other") {
			$input['occupation'] = $request->occupation_other;
		}else {
			$input['occupation'] = $request->occupation;
		}
		$input['birthday'] = Carbon::parse($request->birthday)->format('Y-m-d');
		$input['expiration_date'] = date("Y-10-31");
		$input['offer_year'] = date("Y");

		$this->award = $this->award->create($input);
		$id = $this->award->id;
		$receipt = $this->award->receipt;

		$input['attachment_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($request->name_alphabet)).'.pdf';
		$request->file('attachment')->storeAs(date('Y').'/award', $input['attachment_path'],'noguchi');

		$award = $this->award->findOrFail($id);
		$award->update($input);
		$award_actions = AwardAction::create(['award_id' => $id ,'action' => '新規登録をしました']);

		return redirect(route("awardApplications.index"))->with('success', ['成功'=>'野口遵賞応募を登録しました']);

	}

	public function edit($award_id)
	{
		$award = $this->award->findOrFail($award_id);
		$downloadUrl = config('url.pdfDownload').date('Y', strtotime($award->created_at)).'/award/';
		$downloadUrlList = $downloadUrl.$award->attachment_path;
		return view('backend.awardApplication.create', compact('award', 'downloadUrlList'));
	}

	public function update(UpdateAwardApplicationRequest $request, $award_id)
	{

		$award = $this->award->findOrFail($award_id);

		//$award->user_id = Auth::user()->id;
		$award->receipt = md5(uniqid(rand(), true));
		$award->name = $request->name;
		$award->name_kana = $request->name_kana;
		$award->birthday = $request->birthday;
		$award->belongs = $request->belongs;
		$award->belong_type_name = $request->belong_type_name;
		$award->major = $request->major;

		$applicant_job = $request->occupation;

		if ($applicant_job == "その他") {
			$award->occupation = $request->occupation_other;
		}else {
			$award->occupation = $applicant_job;
		}

		$award->zip_code = $request->zip_code;

		$award->address1 = $request->address1;
		$award->address2 = $request->address2;
		$award->theme = $request->theme;
		$award->subsidy_granted_year = $request->subsidy_granted_year;
		if (!empty($request->attachment)) {
			$award->attachment_path = $award->receipt.'_'.str_replace(" ", "_", strtoupper($request->name_alphabet)).'.pdf';
			$request->file('attachment')->storeAs(date('Y').'/award', $award->attachment_path,'noguchi');
		}

		if($award->name_alphabet != $request->name_alphabet){
			$path = date('Y', strtotime($award->created_at)).'/award';
			$base_path = $path.'/';
			if (!empty($request->attachment)){
				$this->deleteOld($base_path.$award->attachment_path);
				$award_actions = AwardAction::create(['award_id' => $award_id ,'action' => '申請書ファイルを更新しました']);
			} else {
				$new_att = $base_path.$award->receipt.'_'.str_replace(" ", "_", strtoupper($request->name_alphabet)).'.pdf';
				$this->moveOld($base_path.$award->attachment_path, $new_att);
				$award_actions = AwardAction::create(['award_id' => $award_id ,'action' => '氏名（ローマ字）が更新されたのでファイル名を更新しました']);
			}
		}
		$award->name_alphabet = strtoupper($request->name_alphabet);
		$award->save();
		$award_actions = AwardAction::create(['award_id' => $award_id ,'action' => '応募内容を更新しました']);

		return redirect(route("awardApplications.index"))->with('success', ['成功'=>'野口遵賞応募を更新しました']);
	}

	public function confirm($id)
	{
		$award = $this->award->findOrFail($id);
		return view("backend.awardApplication.confirm", compact('award'));
	}


	public function destroy($id)
	{
		$award = $this->award->findOrFail($id);
		$path = date('Y', strtotime($award->created_at)).'/award';
		$base_path = $path.'/';
		$this->deleteOld($base_path.$award->attachment_path);
		$award->delete();
		$award_actions = AwardAction::create(['award_id' => $id ,'action' => '応募を削除しました']);
		return redirect(route("awardApplications.index"))->with("success", ['成功'=>'野口遵賞応募を削除しました']);
	}

	public function downloadCsv()
	{
		//return redirect(route("awardApplications.index"));
		$award = $this->award->get();
		echo "Download List";
	}


	public function updateCustomTopic(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'custom_topic_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return json_encode(array('status' => 'error', 'message' => implode(' ', $validator->messages()->all())));
		}

		foreach ($request->applications as $id) {
			$award = $this->award->findOrFail($id);
			$award->custom_topic_id = $request->custom_topic_id;
			$award->save();
		}

		return json_encode(array('status' => '成功', 'message' => '課題分類の更新が完了しました'));
	}


	public function updateGrantSelected(Request $request)
	{
		foreach ($request->applications as $id) {
			$award = $this->award->findOrFail($id);
			$award->is_granted = 1;
			$award->state = 4;
			$award->save();
		}
		return json_encode(array('status' => 'success', 'message' => '選択を採択しました'));
	}

	public function updateRejectSelected(Request $request)
	{
		foreach ($request->applications as $id) {
			$award = $this->award->findOrFail($id);
			$award->is_granted = 2;
			$award->state = 4;
			$award->save();
		}
		return json_encode(array('status' => 'success', 'message' => '選択を不採択にしました'));
	}

	public function send_mail(Request $request)
	{

		$data = $request->all();
		$send_count = 0;

		foreach ($request->applications as $id) {
			$award = $this->award->findOrFail($id);
			//不採択の場合のみ通知する
			if($award->is_granted == 2){
				$award->mail_sent = 1;
				$award->state = 5;
				$award->save();
				event(new AwardRejectionNoticed($award));
				$send_count += 1;
			}
		}
		if($send_count != 0){
			return json_encode(array('status' => 'success', 'message' => '通知が完了いたしました(不採択の応募のみ通知いたしました)'));
		}else{
			return json_encode(array('status' => 'error', 'message' => '選択された応募に不採択状態の応募がなかったため通知されませんでした'));
		}
	}

	public function renumber(Request $request)
	{
		$config = Config::first();
		$config->next_award_receipt = 1;
		$config->update();

		return json_encode(array('status' => 'success', 'message' => '受付Noが「1」に採番されました'));
	}

	public function relate(Request $request)
	{
		$appId = $request->application;
		$userId = $request->user;

		$app = Award::find($appId);
		$app->user_id = $userId;
		$app->update();

		return json_encode(array('status' => 'success', 'message' => '関連付けが完了しました'));
	}

	public function exportCSV(Request $request)
	{
		$headers = array(
			"Content-Encoding" => "SJIS",
			"Content-type" => "text/csv; charset=SJIS",
			"Content-Disposition" => "attachment; filename=Award-Application-List-".$request->award_year.".csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
		);

		$columns = array();

		$initial_columns = array('受付No',"Email",'氏名', 'フリガナ', '氏名（ローマ字）', '生年月日', '所属機関名', '所属区分', '所属名', '職', '郵便番号', '住所1', '住所2', '研究テーマ名','審査分類', '助成金採択年度', "応募日");

		foreach ($initial_columns as $column) {
			array_push($columns, (mb_convert_encoding($column, "SJIS")));
		}

		$allIds = $request->ids;

		$callback = function() use ($allIds, $columns)
		{
			$file = fopen('php://output', 'w');
			fputcsv($file, $columns);
			foreach ($allIds as $id) {
				$award = $this->award->findOrFail($id);
				$email = isset($award->user) ? $award->user->email : "";
				$award = json_decode(json_encode($award),true);
				$created_at = $award['created_at'];
				$award = str_replace(" " ,"　", $award);
				$award['created_at'] = $created_at;
				$award['custom_topic'] = "";
				if (!empty($award['custom_topic_id'])) {
					$custom = \App\Models\CustomTopic::findOrFail($award['custom_topic_id']);
					$award['custom_topic'] = $custom->name;
					$award['custom_topic'] = str_replace("-", "ー", $custom->name);
				}
				foreach ($award as $key => $value) {
					$award[$key] = mb_convert_encoding($value, "SJIS", "UTF-8");
				}
				fputcsv($file, array($award['receipt'],$email, $award['name'], $award['name_kana'], $award['name_alphabet'], $award['birthday'], $award['belongs'], $award['belong_type_name'], $award['major'], $award['occupation'], $award['zip_code'], $award['address1'], $award['address2'], $award['theme'],$award['custom_topic'], date('Y', strtotime($award['subsidy_granted_year'])), date('Y-m-d', strtotime($award['created_at']))));
			}
			fclose($file);
		};
		return Response::stream($callback, 200, $headers);
	}

	public function awardActionHistory()
	{
		$award_actions = AwardAction::orderBy('created_at', 'desc')->has('award')->get();
		return view('backend.awardApplication.awardActionHistory', compact('award_actions'));
	}

}
