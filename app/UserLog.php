<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;

class UserLog extends Model
{
  use MassPrunable;

  protected $guarded = ['id', 'created_at', 'updated_at'];
  protected $casts = [
    'action_payload' => 'array',
  ];

  public function prunable(){
    return static::where(function($query){
                    $query->where('created_at', '<=', now()->subYear())
                          ->where('level', LOG_NORMAL);
                  })->orWhere(function($query){
                    $query->where('created_at', '<=', now()->subYears(5))
                          ->where('level', LOG_MODERATE);
                  });
  }

	public function user(){
		return $this->belongsTo('App\User');
	}
}
