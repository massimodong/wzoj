<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
	protected $guarded = ['id','created_at','updated_at'];
	public function user(){
		return $this->belongsTo('App\User');
	}

	public function problemset(){
		return $this->belongsTo('App\Problemset');
	}

	public function problem(){
		return $this->belongsTo('App\Problem');
	}

	public function testcases(){
		return $this->hasMany('App\Testcase');
	}
}
