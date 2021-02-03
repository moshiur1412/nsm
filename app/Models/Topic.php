<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = "topics";

        // A Topic has many keywords
    public function keywords()
    {
    	return $this->hasMany(Keyword::class);
    }
}
