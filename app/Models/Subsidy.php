<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsidy extends Model
{
	protected $table = "subsidies";

  protected $fillable = ['receipt', 'user_id', 'state', 'name', 'name_kana', 'name_alphabet', 'birthday', 'belongs', 'belong_type_name', 'major', 'occupation', 'zip_code', 'address1', 'address2', 'theme', 'custom_topic_id', 'topic', 'application_path', 'attachment_path', 'reference_path', 'merged_path', 'is_granted', 'mail_sent', 'offer_year', 'expiration_date', 'valid'];



    	// Each Subsidy belongs to a user
  public function user()
  {
    return $this->belongsTo(Applicant::class);
  }

        // Each Subsidy belongs to a keyword
  public function keyword()
  {
    return $this->belongsTo(Keyword::class);
  }

        // Each Subsidy belongs to a CustomTopic
  public function custom_topic()
  {
    return $this->belongsTo(CustomTopic::class);
  }
}
