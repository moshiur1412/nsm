<?php

namespace App\Models;
use Eloquent;


use Illuminate\Auth\Authenticatable as AuthenticableTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Tokenizer;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\EloquentTrait;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserResetPassword;

class Judge extends Authenticatable
{

	use Notifiable;



	protected $guard = 'judge';
	protected $table = "judges";
	
	protected $fillable = [
		'name', 'email', 'login_password','login_expires_at','last_login_at','valid',
	];


	public function getAuthPassword()
	{
		return $this->login_password;
	}

}
