<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubsidyAction extends Model
{
	protected $table = "subsidy_actions";

	protected $fillable = ['subsidy_id', 'action', 'created_at'];


    	// Each Subsidy belongs to a user
	public function subsidy()
	{
		return $this->belongsTo(Subsidy::class,'subsidy_id');
	}
}
