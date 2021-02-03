<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{

	protected $table = "awards";

	protected $fillable = ['receipt', 'user_id', 'subsidy_id', 'state', 'name', 'name_kana', 'name_alphabet', 'birthday', 'belong_type_name','major', 'belongs', 'occupation', 'zip_code', 'address1', 'address2', 'theme', 'attachment_path', 'is_granted', 'mail_sent', 'offer_year', 'subsidy_granted_year', 'expiration_date', 'valid'];


  		// Each Award belongs to a subsidy
	public function subsidy()
	{
		return $this->belongsTo(Subsidy::class);
	}

  		// Each Award belongs to a user
	public function user()
	{
		return $this->belongsTo(Applicant::class);
	}

		// Each Award belongs to a custom topic
	public function custom_topic()
	{
		return $this->belongsTo(CustomTopic::class);
	}

}
