<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Judge;
use App\Models\Subsidy;
use App\Http\Requests\StoreJudgeRequest;
use App\Http\Requests\UpdateJudgeRequest;
use Auth;
use ZipArchive;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class JudgeController extends Controller
{
	protected $judges;

	public function __construct(Judge $judge)
	{
		$this->judges = $judge;
		$this->middleware('auth:admin');
	}

	public function index()
	{
		$judges = $this->judges->orderBy('created_at', 'desc')->get();
		return view('backend.judges.index', compact('judges'));
	}


	public function create(Judge $judge)
	{
		return view('backend.judges.form', compact('judge'));
	}


	public function store(StoreJudgeRequest $request)
	{
		$judge = $this->judges;
		$judge->email = $request->email;
		$judge->name = $request->name;
		$judge->role = $request->role;
		$judge->valid = isset($request->valid) ? 1 : 0;
		$judge->login_expires_at = date("Y-03-01", strtotime('+1 years'));
		$judge->login_password = bcrypt($request->password);
		$judge->judgecol = 1;
		$judge->save();
		return redirect(route("judges.index"))->with("success", ["成功" => "審査員が登録されました"]);

	}

	public function edit($id)
	{
		$judge = $this->judges->findOrFail($id);
		return view('backend.judges.form', compact('judge'));
	}


	public function update(UpdateJudgeRequest $request, $id)
	{

		$judge = $this->judges->findOrFail($id);
		$judge->email = $request->email;
		$judge->name = $request->name;
		$judge->role = $request->role;
		$judge->login_expires_at = $request->login_expires_at;
		$judge->valid = isset($request->valid) ? 1 : 0;

		$this->validate(request(),[
			'email' => 'required|string|email|max:50|unique:judges,email,'.$id,
		]);


		if(!empty($request->password)){
			$judge->login_password = bcrypt($request->password);
		}

		$judge->save();
		return redirect(route("judges.index"))->with("success", ["成功" => "審査員が更新されました"]);
	}

	public function confirm($id)
	{
		$user = Auth::user();
		if ($user->id == $id) {
			return redirect(route("judges.index"))->with("warning", ["WARNING" =>"You cannot delete Yourself"]);
		}else{
			$judge = $this->judges->findOrFail($id);
			return view("backend.judges.confirm", compact('judge'));
		}
	}


	public function destroy($id)
	{
		$judge = $this->judges->findOrFail($id);
		$judge->delete();
		return redirect(route("judges.index"))->with("success", ["成功" => "審査員が削除されました"]);
	}



}
