<?php

namespace App\Http\Controllers\Subsidy;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Auth;
use App\Events\UserPasswordChanged;
use App\Models\Subsidy;
use App\Models\Award;

class MyPageController extends Controller
{

	public $award;
	public $subsidy;

	function __construct(Subsidy $subsidy, Award $award)
	{
		$this->subsidy = $subsidy;
		$this->award = $award;
		$this->middleware('auth');
	}


	public function index()
	{

		$subsidy = $this->subsidy->where('user_id', Auth::user()->id)->first();
		$award = $this->award->where('user_id', Auth::user()->id)->first();
		if($subsidy != null){
			return redirect()->route('subsidyApply.show', Auth::user()->id);
		}else if($award != null){
			return redirect()->route('awardApply.show', Auth::user()->id);
		}
		return view('subsidy.mypage', compact('award','subsidy'));
	}

	public function showChangePasswordForm()
	{
		return view('subsidy.auth.changePassword');
	}

	public function changePassword(ChangePasswordRequest $request)
	{

        //Change Password
		$user = Auth::user();
		$user->password = bcrypt($request->get('new-password'));
		$user->save();

		event(new UserPasswordChanged($request));
		// return redirect()->back()->with("Success","Password changed successfully!");
		return view('subsidy.auth.changePasswordConfirm');
	}


	public function result()
	{
		$subsidy = $this->subsidy->where('user_id', Auth::user()->id)->first();
		$award = $this->award->where('user_id', Auth::user()->id)->first();
		if($subsidy != null){
			$create_year = date('Y/02/17', strtotime($subsidy->created_at));
		}
		else if($award != null){
			$create_year = date('Y/02/17', strtotime($award->created_at));
		}
		$result_show_date = date('Y/m/d', strtotime("+12 months $create_year"));
		$record = !is_null($subsidy) ? $subsidy : $award;
		if( time() >  strtotime($result_show_date) ){
			return view('subsidy.result', compact('award','subsidy','record'));
		}
		else{
			abort(404);
		}
	}
}
