<?php

namespace App\Http\Controllers\Subsidy\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Input;
use Session;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = 'app/mypage';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {

        return view('subsidy.auth.login');
    }



    public function login(Request $request)
    {


    	$validator = Validator::make($request->all(), [
    		'email' => 'required|email',
    		'password' => 'required',
    	]);

    	if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->login_password, 'email_verified_at' => null])){
    		Auth::guard('web')->logout();
    		return redirect()->back()->withErrors(["受信ボックスを確認してアカウントを有効化してください"]);

    	}else if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->login_password, 'valid' => 1])){

    		return redirect()->intended('app/mypage') ?: redirect('app/mypage');
    	}
    	return redirect()->back()->withInput(Input::only('email'))->withErrors(['ユーザーとパスワードが一致しません']);
    }



    public function logout(Request $request)
    {
        $request->session()->flush();

        Auth::guard('web')->logout();

        return redirect(route('app.login'));
    }




}
