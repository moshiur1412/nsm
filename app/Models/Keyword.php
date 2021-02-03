<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = "keywords";

		// Each Keyword belongs to a topic
	public function topic()
	{
		return $this->belongsTo(Topic::class);
	}

        // A Keyword has many subsidies
    public function subsidies()
    {
        return $this->hasMany(Subsidy::class);
    }
}
