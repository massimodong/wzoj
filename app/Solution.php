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
		'testcases' => 'array',
	];

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function judger(){
		return $this->belongsTo('App\Judger');
	}

	public function problemset(){
		return $this->belongsTo('App\Problemset');
	}

	public function problem(){
		return $this->belongsTo('App\Problem');
	}

	public function getTestcasesAttribute($value){
		return json_decode($value);
	}

	public function answerfiles(){
		return $this->hasMany('App\Answerfile');
	}

	public function sim(){
		return $this->belongsTo('App\Sim');
	}

	//public part of the solutions
	public function scopePublic($query){
		$query->select(['solutions.id', 'solutions.user_id', 'solutions.problem_id', 'solutions.score', 'solutions.status',
				'solutions.time_used', 'solutions.memory_used','solutions.language', 'solutions.code_length',
				'solutions.judger_id', 'solutions.judged_at', 'solutions.sim_id', 'solutions.created_at'])
			->addSelect(DB::raw('solutions.ce is NOT NULL as ce'))
			->with(['user' => function($query){
				$query->select(['id', 'name', 'fullname', 'class']);
			  }])
			->with(['problem' => function($query){
				$query->select(['id', 'name']);
			  }])
			->with(['judger' => function($query){
				$query->select(['id', 'name']);
			  }])
			->with('sim');
	}
}
