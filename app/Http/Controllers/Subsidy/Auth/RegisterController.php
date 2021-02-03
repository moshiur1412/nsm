<?php

namespace App\Http\Controllers\Subsidy\Auth;

use App\Models\Applicant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreApplicantRequest;
use App\Events\UserVerified;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest');
    }

    public function showRegistrationForm()
    {

    	return view('subsidy.auth.register');
    }

    public function register(StoreApplicantRequest $request)
    {
        $token = hash("sha256",$request['user-email']);
    	Applicant::create([
            'name' => $request['name'],
            'name_kana' => $request['name_kana'],
            'belong_type_name' => $request['belong_type_name'],
            'belongs' => $request['belongs'],
            'major' => $request['major'],
    		'email' => $request['user-email'],
    		'password' => Hash::make($request['user-password']),
    		'email_verified_token' => $token,
            'expiration_date' => date("Y-03-31", strtotime('+1 years'))
    	]);
        $request['_token'] = $token;
        event(new UserVerified($request));
        \Log::info('Req=Subsidy/Auth/RegisterController');
    	return redirect('app/login')->with('message','仮登録が完了しました。受信ボックスを確認してください。');
        // return view('subsidy.auth.done');
    }

    public function verifyUser($token)
    {
    	$verifyUser = Applicant::where('email_verified_token', $token)->first();
    	if(isset($verifyUser) ){
    		if(!$verifyUser->email_verified_at)
    		{
    			$verifyUser->email_verified_at = date('Y-m-d H:i:s');
    			$verifyUser->save();
    			return view('subsidy.auth.done')->with('success', 'Your e-mail is verified. You can login now.');
    		} else {
    			return view('subsidy.auth.done')->with('success', 'Your e-mail is already verified. You can login now.');

    		}
    	} else {
    		return redirect('app/login')->withErrors(['Sorry your email cannot be identified.']);
    	}
    	return redirect('app/login')->withErrors(['Something woring, please recheck.']);
    }



}
