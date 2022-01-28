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

  //TODO: time based on level
  public function prunable(){
    return static::where('created_at', '<=', now()->subMonth());
  }

	public function user(){
		return $this->belongsTo('App\User');
	}
}
