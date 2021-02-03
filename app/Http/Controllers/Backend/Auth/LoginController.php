<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Judge;
use App\Models\Admin;
use Validator;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'back/admins';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest:admin')->except('logout');
    	$this->middleware('guest:judge')->except('logout');

    }

    public function showLoginForm()
    {

    	return view('backend.auth.login');
    }


    public function login(Request $request)
    {

    	$validation = Validator::make($request->all(), [
    		'email' => 'required|email',
    		'login_password' => 'required',
    	]);


    	$judgeChecked = $request->judge;

    	$credentials = array(
    		'email' => $request->email,
    		'login_password' => $request->login_password,
    		'valid' => 1
    	);

    	if($judgeChecked != null){
    		if(Auth::guard('judge')->attempt($credentials)){
    			return redirect()->intended('back/judgeSubsidyApplication');
    		}else{
    			return redirect()->back()->withInput(Input::only('email'))->withErrors(['IDまたはパスワードが正しくありません']);
    		}
    	}else{
    		if (Auth::guard('admin')->attempt($credentials)) {
    			return redirect()->intended(route('admins.index'));
    		}else{
    			return redirect()->back()->withInput(Input::only('email'))->withErrors(['IDまたはパスワードが正しくありません']);
    		}

    	}


    }




    public function logout(Request $request)
    {

    	$request->session()->flush();

    	Auth::guard('admin')->logout();
    	Auth::guard('judge')->logout();

    	return redirect(route('back.login'));
    }


}
