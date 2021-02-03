<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Auth;


class AdminController extends Controller
{
	protected $admin;

	public function __construct(Admin $admin)
	{
		$this->admin = $admin;
		$this->middleware('auth:admin');
	}

	public function index()
	{
		$admins = $this->admin->orderBy('created_at', 'desc')->get();
		return view('backend.admins.index', compact('admins'));
	}

	public function create(Admin $admin)
	{
		return view('backend.admins.form', compact('admin'));
	}

	public function store(StoreAdminRequest $request)
	{
		$admin = $this->admin;
		$admin->email = $request->email;
		$admin->name = $request->name;
		$admin->valid = isset($request->valid) ? 1 : 0;
		$admin->login_password = bcrypt($request->password);
		//echo $admin->valid;
		$admin->save();
		return redirect(route("admins.index"))->with("success", ["成功"=>"管理者を登録しました"]);
	}

	public function edit($id)
	{
		$admin = $this->admin->findOrFail($id);
		return view('backend.admins.form', compact('admin'));
	}


	public function update(UpdateAdminRequest $request, $id)
	{
		$admin = $this->admin->findOrFail($id);
		$admin->email = $request->email;
		$admin->name = $request->name;
		$admin->valid = isset($request->valid) ? 1 : 0;

		$this->validate(request(),[
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:50|unique:admins,email,'.$id,
		]);


		if(!empty($request->password)){
			$admin->login_password = bcrypt($request->password);
		}

		$admin->save();
		return redirect(route("admins.index"))->with("success", ["成功"=>"管理者を更新しました"]);
	}

	public function destroy($id)
	{
		$admin = $this->admin->findOrFail($id);
		$admin->delete();
		return redirect(route("admins.index"))->with("success", ["成功"=>"管理者を削除しました"]);


	}

}
