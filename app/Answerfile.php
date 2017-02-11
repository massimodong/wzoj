<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answerfile extends Model
{
	protected $guarded = ["id", "created_at", "updated_at"];
	public function solution(){
		return $this->belongsTo('App\Solution');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}
}
