<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Solution extends Model
{
	protected $guarded = ['id','created_at','updated_at'];
	protected $casts =[
		'id' => 'integer',
		'user_id' => 'integer',
		'problem_id' => 'integer',
		'problemset_id' => 'integer',
		'language' => 'integer',
		'code_length' => 'integer',
		'time_used' => 'integer',
		'memory_used' => 'double',
		'status' => 'integer',
		'score' => 'integer',
		'sim_id' => 'integer',
		'judger_id' => 'integer',
		'cnt_testcases' => 'integer',
	];

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function judger(){
		return $this->belongsTo('App\User', 'judger_id');
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
				'language', 'code_length', 'judger_id', 'judged_at', 'created_at'])
			->addSelect(DB::raw('ce is NOT NULL as ce'))
			->with(['user' => function($query){
				$query->select(['id', 'name', 'fullname', 'class']);
			  }])
			->with(['problem' => function($query){
				$query->select(['id', 'name']);
			  }])
			->with(['judger' => function($query){
				$query->select(['id', 'name', 'fullname', 'class']);
			  }]);
	}
}
