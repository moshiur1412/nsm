<?php

namespace App\Http\Controllers\Backend;

use Mail;
use Storage;
use Response;
use Validator;
use Carbon\Carbon;
use App\Rules\Kana;
use App\Models\Topic;
use App\Rules\Romaji;
use App\Rules\SpaceIn;
use App\Rules\NoSymbol;
use App\Models\Keyword;
use App\Models\Subsidy;
use App\Models\CustomTopic;
use App\Models\Applicant;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\SubsidyAction;
use App\Http\Controllers\Controller;
use App\Events\SubsidyRejectionNoticed;

use Log;

class SubsidyApplicationController extends Controller
{

    protected $topic;
    protected $subsidy;
    protected $keyword;
    protected $custom_topic;

    function __construct(Subsidy $subsidy, Topic $topic, Keyword $keyword, CustomTopic $custom_topic)
    {
        $this->middleware('auth:admin');
        $this->topic = $topic;
        $this->subsidy = $subsidy;
        $this->keyword = $keyword;
        $this->custom_topic = $custom_topic;
        $this->updateState();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selected_year = empty(\Request::get('selected_year')) ? date('Y') : \Request::get('selected_year');
        $offer_years = $this->subsidy->select('offer_year')->groupBy('offer_year')->orderBy('offer_year', 'desc')->where('valid',1)->get();
        $years = [];
        foreach ($offer_years as $obj) {
            $years[] = $obj->offer_year;
        }
        $states = config('states');
        $custom_categories = $this->custom_topic->get();
        $subsidies = $this->subsidy->orderBy('created_at', 'desc')->whereRaw('offer_year = ?', array($selected_year))->get();
        $applicants = Applicant::all();

        $config = Config::first();
        $next_no = $config->next_subsidy_receipt;
        return view('backend.subsidyApplications.index', compact('subsidies', 'states', 'selected_year', 'years', 'custom_categories','applicants','next_no'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = $this->topic->where('valid', 1)->get();
        return view('backend.subsidyApplications.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required','string',new NoSymbol($request->all()),new SpaceIn($request->all())],
            'name_kana' => ['required','string',new NoSymbol($request->all()),new SpaceIn($request->all()),new Kana($request->all())],
            'name_alphabet' => ['required','string',new Romaji($request->all()),new NoSymbol($request->all()),new SpaceIn($request->all())],
            'birthday' => 'required|after_or_equal:'.Carbon::parse(config('expirationdate'))->format('Y-m-d'),
            'belongs' => 'required|string|min:2|max:255',
            'belong_type_name' => 'required|string|min:2|max:255',
            'major' => 'required|string|min:2|max:255',
            'occupation' => 'required|string|min:2|max:255',
            'occupation_other'=>'required_if:occupation,"その他"',
            'zip_code' => 'required|string|min:2|max:255',
            'address1' => 'required|max:1000',
            'theme' => 'required|string|max:40',
            'topic' => 'required|numeric',
            'application' => 'required|mimes:pdf|max:5000',
            'attachment' => 'required|mimes:pdf|max:5000',
            'reference' => 'required|mimes:pdf|max:5000',
        ], [
    			'birthday.after_or_equal'  => ':attributeが:date以前の方は応募対象外となります。',
        ]);

        $input = $request->all();
        $input['state'] = 2;
        $input['birthday'] = Carbon::parse($input['birthday'])->format('Y-m-d');
        $input['expiration_date'] = date("Y-10-31");
        $input['offer_year'] = date("Y");

        if(!empty($input['occupation_other']) && $input['occupation'] == 'その他'){
            $input['occupation'] = $input['occupation_other'];
        }

        $keyword = $this->keyword->findOrFail($request->topic);
        $input['topic'] = json_encode([
            'keyword' => array('id' => $keyword->id, 'name' => $keyword->name, 'prefix' => $keyword->prefix),
            'topic' => array('id' => $keyword->topic->id, 'name' => $keyword->topic->name, 'description' => $keyword->topic->description)
        ]);

        $input['application_path'] = '';
        $input['attachment_path'] = '';
        $input['merged_path'] = '';
        $input['reference_path'] = '';

        $this->subsidy = $this->subsidy->create($input);
        $id = $this->subsidy->id;
        $receipt = $this->subsidy->receipt;

        $input['application_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_1.pdf';
        $input['attachment_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_2.pdf';
        $input['merged_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_3.pdf';
        $input['reference_path'] = $receipt.'_'.str_replace(" ", "_", strtoupper($input['name_alphabet'])).'_4.pdf';

        $request->file('application')->storeAs(date('Y').'/subsidy', $input['application_path'],'noguchi');
        $request->file('attachment')->storeAs(date('Y').'/subsidy', $input['attachment_path'],'noguchi');
        $path = date('Y').'/subsidy';
        $this->storeMerged($path.'/'.$input['application_path'],$path.'/'.$input['attachment_path'],$path.'/'.$input['merged_path']);

        $request->file('reference')->storeAs(date('Y').'/subsidy', $input['reference_path'],'noguchi');

        $subsidy = $this->subsidy->findOrFail($id);
        $subsidy->update($input);
        $subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '新規登録をしました']);

        return redirect()->route('subsidyApplications.index')->with('success', ['成功' => '助成金応募が完了しました']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('subsidyApplications.edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subsidy = $this->subsidy->findOrFail($id);
        $states = config('states');
        $categories = $this->topic->where('valid', 1)->get();
        $downloadUrl = config('url.pdfDownload').date('Y', strtotime($subsidy->created_at)).'/subsidy/';
        $downloadUrlList = [];
        $downloadUrlList[0] = $downloadUrl.$subsidy->application_path;
        $downloadUrlList[1] = $downloadUrl.$subsidy->attachment_path;
        $downloadUrlList[2] = $downloadUrl.$subsidy->merged_path;
        $downloadUrlList[3] = $downloadUrl.$subsidy->reference_path;
        return view('backend.subsidyApplications.edit', compact('states', 'subsidy', 'categories', 'downloadUrlList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            'occupation_other'=>'required_if:occupation,"その他"',
            'zip_code' => 'required|string|min:2|max:255',
            'address1' => 'required|max:1000',
            'theme' => 'required|string|max:40',
            'topic' => 'numeric',
            'application' => 'mimes:pdf|max:5000|required_with:attachment',
            'attachment' => 'mimes:pdf|max:5000|required_with:application',
            'reference' => 'mimes:pdf|max:5000',
        ], [
    			'birthday.after_or_equal'  => ':attributeが:date以前の方は応募対象外となります。',
        ]);

        $input = $request->all();
        $subsidy = $this->subsidy->findOrFail($id);

        if(!empty($input['occupation_other']) && $input['occupation'] == 'その他'){
            $input['occupation'] = $input['occupation_other'];
        }

        $input['birthday'] = Carbon::parse($input['birthday'])->format('Y-m-d');
        $input['expiration_date'] = Carbon::parse($input['expiration_date'])->format('Y-m-d');

        $input['topic'] = $subsidy->topic;
        if($request->topic != 0){
            $keyword = $this->keyword->findOrFail($request->topic);
            $input['topic'] = json_encode([
                'keyword' => array('id' => $keyword->id, 'name' => $keyword->name, 'prefix' => $keyword->prefix),
                'topic' => array('id' => $keyword->topic->id, 'name' => $keyword->topic->name, 'description' => $keyword->topic->description)
            ]);
        }

        if (empty($input['valid'])){
            $input['valid'] = 0;
        }

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
            $this->storeMerged($path.'/'.$input['application_path'],$path.'/'.$input['attachment_path'],$path.'/'.$input['merged_path']);
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
                $this->deleteOld($base_path.$subsidy->reference_path);
            } else if (!empty($input['reference'])) {
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
                $this->moveOld($base_path.$subsidy->application_path, $new_app);
                $this->moveOld($base_path.$subsidy->attachment_path, $new_att);
                $this->moveOld($base_path.$subsidy->merged_path, $new_mrg);
                $this->moveOld($base_path.$subsidy->reference_path, $new_ref);
                $subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '氏名（ローマ字）が更新されたのでファイル名を更新しました']);
            }
        }

        $subsidy->update($input);
        $subsidy_actions = SubsidyAction::create(['subsidy_id' => $id ,'action' => '応募内容を更新しました']);

        return redirect()->route('subsidyApplications.edit', $id)->with('success', ['成功' => '助成金応募が更新されました']);
    }

    private function updateState()
    {
        $subsidies = $this->subsidy->orderBy('created_at', 'desc')->whereRaw('year(`created_at`) = ?', array(date('Y')))->get();
        foreach ($subsidies as $subsidy) {
            if($subsidy->state == 2 && (empty($subsidy->expiration_date) && time() > strtotime(date('Y/10/31'))) || (!empty($subsidy->expiration_date) && strtotime('today') != strtotime(date('Y/10/31')) && time() > strtotime($subsidy->expiration_date))){
                $sub_get = $this->subsidy->findOrFail($subsidy->id);
                $sub_get->state = 3;
                $sub_get->save();
            }
        }
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
            $subsidy = $this->subsidy->findOrFail($id);
            $subsidy->custom_topic_id = $request->custom_topic_id;
            $subsidy->state = 2;
            $subsidy->save();
        }

        return json_encode(array('status' => '成功', 'message' => '課題分類の更新が完了しました'));
    }

    public function updateGrantPrimarySelected(Request $request)
    {
        foreach ($request->applications as $id) {
            $subsidy = $this->subsidy->findOrFail($id);
            $subsidy->primary_granted = 1;
            $subsidy->save();
        }
        return json_encode(array('status' => '成功', 'message' => '選択を一次通過状態にしました'));
    }

    public function updateGrantPrimaryCanceled(Request $request)
    {
        foreach ($request->applications as $id) {
            $subsidy = $this->subsidy->findOrFail($id);
            $subsidy->primary_granted = 0;
            $subsidy->is_granted = 0;
            $subsidy->save();
        }
        return json_encode(array('status' => '成功', 'message' => '選択を一次未通過状態にしました'));
    }


    public function updateGrantSelected(Request $request)
    {
        foreach ($request->applications as $id) {
            $subsidy = $this->subsidy->findOrFail($id);
            if($subsidy->primary_granted == 1){
                $subsidy->is_granted = 1;
                $subsidy->state = 4;
                $subsidy->save();
            }
        }
        return json_encode(array('status' => '成功', 'message' => '選択を採択状態にしました'));
    }

    public function updateRejectSelected(Request $request)
    {
        foreach ($request->applications as $id) {
            $subsidy = $this->subsidy->findOrFail($id);
            $subsidy->is_granted = 2;
            $subsidy->state = 4;
            $subsidy->save();
        }
        return json_encode(array('status' => '成功', 'message' => '選択を不採択にしました'));
    }

    public function sendMail(Request $request)
    {

  		$data = $request->all();
  		$send_count = 0;

  		foreach ($request->applications as $id) {
  			$subsidy = $this->subsidy->findOrFail($id);

  			//不採択の場合のみ通知する
  			if($subsidy->is_granted == 2){
  				$subsidy->mail_sent = 1;
  				$subsidy->state = 5;
  				$subsidy->save();
  				event(new SubsidyRejectionNoticed($subsidy));
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
        $config->next_subsidy_receipt = 1;
        $config->update();

        return json_encode(array('status' => 'success', 'message' => '受付Noが「1」に採番されました'));
    }

    public function relate(Request $request)
    {
        $appId = $request->application;
        $userId = $request->user;

        $app = Subsidy::find($appId);
        $app->user_id = $userId;
        $app->update();

        return json_encode(array('status' => 'success', 'message' => '関連付けが完了しました'));
    }

    public function exportCSV(Request $request)
    {
        $headers = array(
            "Content-Encoding" => "SJIS",
            "Content-type" => "text/csv; charset=SJIS",
            "Content-Disposition" => "attachment; filename=Subsidy-Application-List-".$request->selected_year.".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array();

        $initial_columns = array("受付No", "Email","氏名", "フリガナ", "氏名（ローマ字）", "生年月日", "所属区分", "所属機関名", "所属名", "職", "郵便番号", "住所1", "住所2", "研究テーマ名", "審査分類","課題","キーワード", "応募日");

        foreach ($initial_columns as $column) {
            array_push($columns, (mb_convert_encoding($column, "SJIS")));
        }

        $allIds = $request->ids;

        $callback = function() use ($allIds, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($allIds as $id) {
                $subsidy = $this->subsidy->findOrFail($id);
                $email = isset($subsidy->user) ? $subsidy->user->email : "";
                $subsidy = json_decode(json_encode($subsidy),true);
                $topic = json_decode($subsidy['topic'], true);
                $subsidy['custom_topic'] = "";
                if (!empty($subsidy['custom_topic_id'])) {
                    $custom = \App\Models\CustomTopic::findOrFail($subsidy['custom_topic_id']);
                    $subsidy['custom_topic'] = $custom->name;
                    $subsidy['custom_topic'] = str_replace("-", "ー", $custom->name);
                }
                $subsidy['topic'] = $topic['topic']['name']."：".$topic['topic']['description'];
                $subsidy['keyword'] = $topic['keyword']['name'];
                $created_at = $subsidy['created_at'];
                $subsidy = str_replace(" " ,"　", $subsidy);
                $subsidy['created_at'] = $created_at;
                foreach ($subsidy as $key => $value) {
                    $subsidy[$key] = mb_convert_encoding($value, "SJIS-win", "AUTO");
                }
                fputcsv($file, array($subsidy['receipt'], $email, $subsidy['name'], $subsidy['name_kana'], $subsidy['name_alphabet'], $subsidy['birthday'], $subsidy['belong_type_name'], $subsidy['belongs'], $subsidy['major'], $subsidy['occupation'], $subsidy['zip_code'], $subsidy['address1'], $subsidy['address2'], $subsidy['theme'], $subsidy['custom_topic'],$subsidy['topic'], $subsidy['keyword'],date('Y-m-d', strtotime($subsidy['created_at']))));

            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
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

        return redirect()->route('subsidyApplications.index')->with('success', ['成功' => '助成金応募が削除されました']);
    }

    public function subsidyActionHistory()
    {
        $subsidy_actions = SubsidyAction::orderBy('created_at', 'desc')->has('subsidy')->get();
        return view('backend.subsidyApplications.subsidyActionHistory', compact('subsidy_actions'));
    }
}
