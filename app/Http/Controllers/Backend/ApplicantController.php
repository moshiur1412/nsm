<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Award;
use App\Models\Subsidy;
use App\Http\Requests\StoreAppplicantUserRequest;
use App\Http\Requests\UpdateApplicantUserRequest;
use Auth;


class ApplicantController extends Controller
{
	protected $applicant;
	protected $award;
	protected $subsidy;

	public function __construct(Applicant $applicant, Award $award, Subsidy $subsidy)
	{
		$this->applicant = $applicant;
		$this->award = $award;
		$this->subsidy = $subsidy;
		$this->middleware('auth:admin');
	}

	public function index()
	{
		$applicants = $this->applicant->orderBy('created_at', 'desc')->get();
		$award = $this->award->get();
		$subsidy = $this->subsidy->get();
		return view('backend.applicants.index', compact('applicants','award','subsidy'));
	}

	public function create(Applicant $applicant)
	{
		return view('backend.applicants.form', compact('applicant'));
	}

	public function store(StoreAppplicantUserRequest $request)
	{
		$applicant = $this->applicant;
		$applicant->name = $request->name;
		$applicant->name_kana = $request->name_kana;
		$applicant->belong_type_name = $request->belong_type_name;
		$applicant->belongs = $request->belongs;
		$applicant->major = $request->major;
		$applicant->email = $request->email;
		$applicant->valid = isset($request->valid) ? 1 : 0;
		$applicant->password = bcrypt($request->password);
		$applicant->expiration_date = date("Y-03-31", strtotime('+1 years'));
		$applicant->save();
		return redirect(route("applicants.index"))->with("success", ["成功"=>"ユーザーが登録されました"]);
	}

	public function edit($id)
	{
		$applicant = $this->applicant->findOrFail($id);
		return view('backend.applicants.form', compact('applicant'));
	}


	public function update(UpdateApplicantUserRequest $request, $id)
	{
		$applicant = $this->applicant->findOrFail($id);
		$applicant->name = $request->name;
		$applicant->name_kana = $request->name_kana;
		$applicant->belong_type_name = $request->belong_type_name;
		$applicant->belongs = $request->belongs;
		$applicant->major = $request->major;
		$applicant->email = $request->email;
		$applicant->valid = isset($request->valid) ? 1 : 0;
		$applicant->expiration_date = date("Y-03-31", strtotime('+1 years'));
		if(!empty($request->password)){
			$applicant->password = bcrypt($request->password);
		}

		$applicant->save();
		return redirect(route("applicants.index"))->with("success", ["成功"=>"ユーザーが更新されました"]);
	}

	public function destroy($id)
	{
		$applicant = $this->applicant->findOrFail($id);
		$applicant->delete();
		return redirect(route("applicants.index"))->with("success", ["成功"=>"ユーザーが削除されました"]);
	}

}
