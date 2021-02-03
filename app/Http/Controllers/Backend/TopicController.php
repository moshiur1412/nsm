<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\Topic;
use DB;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use Auth;
use App\Http\Requests\KeywordSelectionRequest;
use Illuminate\Foundation\Http\FormRequest;

class TopicController extends Controller
{
	protected $keywords;
	protected $topics;

	public function __construct(Topic $topic, Keyword $keyword)
	{
		$this->middleware('auth:admin');
        $this->keywords = $keyword;
        $this->topics = $topic;

    }

    public function index()
    {

        $topics = $this->topics->orderBy('created_at', 'desc')->get();
        return view('backend.topics.index', compact('topics'));
    }

    public function create(Topic $topic)
    {
       return view('backend.topics.form', compact('topic'));
   }

   public function store(StoreTopicRequest $request)
   {
       $topic = $this->topics;
       $topic->name = $request->name;
       $topic->description = $request->description;

       $topic->save();
       return redirect(route("topics.index"))->with("success", ['成功' => "課題を登録しました"]);
   }

   public function edit($id)
   {
       $topic = $this->topics->findOrFail($id);
       $keywords = $this->keywords->get();
       return view('backend.topics.form', compact('topic','keywords'));
		//return view('backend.keywords.form', compact('keyword', 'topic'));
   }

   public function update(UpdateTopicRequest $request, $id)
   {
       $topic = $this->topics->findOrFail($id);
       $topic->name = $request->name;
       $topic->description = $request->description;

       $this->validate(request(),[
          'name' => 'required|string|max:255|unique:topics,name,'.$id,
      ]);

       $topic->save();

		//print_r($request->topic_keyword). "<br/>";

		/* if(!empty($request->topic_keyword)) {
    		foreach($request->topic_keyword as $key) {
            	echo $key;
   			 	}
   			 }*/

   			 return redirect(route("topics.index"))->with("success", ['成功' => "課題を更新しました"]);
		/*echo $topic->name . "<br/>";
		echo $topic->description . "<br/>";*/

		//return view('backend.topics.form', compact('topic'));
	}


	public function confirm($id)
	{
		$topic = $this->topics->findOrFail($id);
		return view("backend.topics.confirm", compact('topic'));
	}

	public function destroy($id)
	{
		$topic = $this->topics->findOrFail($id);
		$topic->delete();
		return redirect(route("topics.index"))->with("success", ['成功' => "課題を削除しました"]);
	}


    public function keyword()
    {
        $topics = $this->topics->get();
        $keywords = $this->keywords->whereNull('topic_id')->get();
		$keywordIds = json_encode($this->keywords->pluck('id')->all());
        return view('backend.topics.match', compact('topics','keywords','keywordIds'));
    }

    public function sortKeyword(Request $request)
    {
        try{
			$keywords = $this->keywords->all();
			$new_data = [];
			foreach ($request->formatted as $key => $new_record) {
				foreach ($new_record as $keyword_id => $topic_id) {
					$old_record = $this->getModelById($keywords,$keyword_id);
					$updated_record = [
						'topic_id' => $topic_id == 0 ? null : $topic_id,
						'prefix' => $old_record->prefix,
						'name' => $old_record->name,
						'created_at' => $old_record->created_at,
						'updated_at' => $old_record->updated_at,
					];
					$new_data[] = $updated_record;
				}
			}
			$this->keywords->truncate();
			\DB::table('keywords')->insert($new_data);
            return ['status'=>200, 'message'=>'Keyword saved!'];
        }
        catch (\Exception $e) {
            return ['status'=>401, 'message'=>$e->getMessage()];
        }
    }

	private function getModelById($model,$id) {
		return $model->first(function($item) use ($id) {
    		return $item->id == $id;
		});
	}

}
