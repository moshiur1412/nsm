<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTopic extends Model
{
	protected $table = "custom_topics";

	protected $fillable = [
		'name'
	];

        // A Topic has many subsidies
	public function subsidies()
	{
		return $this->hasMany(Subsidy::class);
	}

	public function awards()
	{
		return $this->hasMany(Award::class);
	}
}
