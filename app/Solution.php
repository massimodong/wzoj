<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

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

	public function answerfiles(){
		return $this->hasMany('App\Answerfile');
	}

	//public part of the solutions
	public function scopePublic($query){
		$query->select(['id', 'user_id', 'problem_id', 'score', 'status', 'time_used', 'memory_used',
				'language', 'code_length', 'judged_at'])
			->addSelect(DB::raw('ce is NOT NULL as ce'))
			->with(['user' => function($query){
				$query->select(['id', 'name', 'fullname', 'class']);
			  }])
			->with(['problem' => function($query){
				$query->select(['id', 'name']);
			  }]);
	}
}
