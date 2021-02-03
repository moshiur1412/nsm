<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\Topic;
use App\Http\Requests\StoreKeywordRequest;
use App\Http\Requests\UpdateKeywordRequest;
use Auth;


class KeywordController extends Controller
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
		$keywords = $this->keywords->orderBy('created_at', 'desc')->get();
		return view('backend.keywords.index', compact('keywords'));
	}


	public function create(Keyword $keyword)
	{
		return view('backend.keywords.form', compact('keyword'));
	}

	public function store(StoreKeywordRequest $request)
	{
		$keyword = $this->keywords;
		$keyword->name = $request->name;
		$keyword->prefix = $request->prefix;
		$keyword->save();
		return redirect(route("keywords.index"))->with("success", ["成功" => "キーワードを登録しました"]);
	}

	public function edit($id)
	{
		$keyword = $this->keywords->findOrFail($id);
		return view('backend.keywords.form', compact('keyword'));
	}


	public function update(UpdateKeywordRequest $request, $id)
	{
		$keyword = $this->keywords->findOrFail($id);
		$keyword->name = $request->name;
		$keyword->prefix = $request->prefix;
		$this->validate(request(),[
			'prefix' => 'required|string|max:255|unique:keywords,prefix,'.$id,
		]);
		$keyword->save();
		return redirect(route("keywords.index"))->with("success", ["成功" => "キーワードを更新しました"]);

	}


	public function confirm($id)
	{
		$keyword = $this->keywords->findOrFail($id);
		return view("backend.keywords.confirm", compact('keyword'));
	}

	public function destroy($id)
	{
		$keyword = $this->keywords->findOrFail($id);
		$keyword->delete();
		return redirect(route("keywords.index"))->with("success", ["成功" => "キーワードを削除しました"]);
	}


}
