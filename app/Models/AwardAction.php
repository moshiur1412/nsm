<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwardAction extends Model
{
  protected $table = "award_actions";

	protected $fillable = ['award_id', 'action', 'created_at'];

  public function award()
  {
      return $this->belongsTo(Award::class,'award_id');
  }
}
