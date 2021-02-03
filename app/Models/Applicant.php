<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;
    protected $table = "applicants";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    // 	'email', 'password','email_verified_token','expiration_date',
    // ];
    
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'email_verified_at' => 'datetime',
    ];

    public function awards()
    {
        return $this->hasOne(Award::class,'user_id');
    }

    public function subsidies()
    {
        return $this->hasOne(Subsidy::class,'user_id');
    }
}
