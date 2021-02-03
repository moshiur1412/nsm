<?php

namespace App\Http\Controllers\Backend;

use App\Models\CustomTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomTopicController extends Controller
{
    protected $customTopic;

    function __construct(CustomTopic $customTopic)
    {
        $this->customTopic = $customTopic;
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $custom_categories = $this->customTopic->orderBy('created_at', 'desc')->get();
        return view('backend.customTopics.index', compact('custom_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customTopics.create');
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
            'name' => 'required|string|min:1|max:255'
        ]);
        $input = $request->all();
        $this->customTopic->create($input);
        return redirect()->route('customTopics.index')->with('success', ["成功" => '審査分類を登録しました']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('customTopics.edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->customTopic->findOrFail($id);
        return view('backend.customTopics.edit', compact('category'));
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
            'name' => 'required|string|min:2|max:255'
        ]);

        $input = $request->all();
        $category = $this->customTopic->findOrFail($id);
        $category->update($input);
        return redirect()->route('customTopics.index')->with('success', ["成功" => '審査分類を更新しました']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->customTopic->findOrFail($id);
        $category->delete();
        return redirect()->route('customTopics.index')->with('success', ["成功" => '審査分類を削除しました']);
    }
}
