<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Auth;

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
		$query->select(['solutions.id', 'solutions.user_id', 'solutions.problem_id', 'solutions.problemset_id', 'solutions.score', 'solutions.status',
				'solutions.time_used', 'solutions.memory_used','solutions.language', 'solutions.code_length', 'solutions.testcases', 'solutions.cnt_testcases',
				'solutions.judger_id', 'solutions.judged_at', 'solutions.sim_id', 'solutions.created_at'])
			->addSelect(DB::raw('solutions.ce is NOT NULL as ce'))
			->with(['user' => function($query){
				$query->select(['id', 'name', 'nickname', 'avatar_token', 'stored_description', 'description_changed_at', 'new_description']);
			  }])
			->with(['problem' => function($query){
				$query->select(['id', 'name']);
			  }])
			->with(['judger' => function($query){
				$query->select(['id', 'name']);
			  }])
			->with(['problemset' => function($query){
				$query->select(['id', 'type']);
			  }])
			->with('sim');
	}

	/**
	  * show only solutions not in a running contest
	  */
	public function scopeNohidden($query){
		if(Auth::check() && Auth::user()->has_role('admin')) return;

		$query->leftJoin(DB::raw("(SELECT `id` as `aux_id` FROM `problemsets` WHERE `contest_hide_solutions` = 1 AND `type` = 'apio' AND `contest_end_at` >= '".date('Y-m-d H:i:s')."') aux"), 'solutions.problemset_id', '=', 'aux.aux_id')
			->where(function($query){
				$query = $query->whereNull('aux.aux_id');
				if(Auth::check()) $query->orWhere('solutions.user_id', Auth::user()->id);
			});
	}

	public function shouldShowSim(){
		if($this->sim->rate < ojoption('sim_threshold')) return false;
		if($this->problemset->type === 'set'){
			if($this->score < 100) return false;
		}else{
			if($this->score < 30) return false;
		}
		return true;
	}

	public function publicAttr(){
		return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'problem_id' => $this->problem_id,
			'problemset_id' => $this->problemset_id,
			'language' => $this->language,
			'code_length' => $this->code_length,
			'time_used' => $this->time_used,
			'memory_used' => $this->memory_used,
			'status' => $this->status,
			'score' => $this->score,
			'ce' => $this->ce,
			'testcases' => $this->testcases,
			'sim_id' => $this->sim_id,
			'judged_at' => $this->judged_at,
			'judger_id' => $this->judger_id,
			'cnt_testcases' => $this->cnt_testcases,
			'created_at' => $this->created_at,

			'user' => [
				'id' => $this->user->id,
				'name' => $this->user->name,
				'nickname' => $this->user->nickname,
			],
			'problem' => [
				'id' => $this->problem->id,
				'name' => $this->problem->name,
			],
			'judger' => [
				'id' => isset($this->judger)?$this->judger->id:0,
				'name' => isset($this->judger)?$this->judger->name:'',
			],
		];
	}

	public function publicAttrLess(){
		return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'problem_id' => $this->problem_id,
			'problemset_id' => $this->problemset_id,
			'language' => $this->language,
			'code_length' => $this->code_length,
			'time_used' => $this->time_used,
			'memory_used' => $this->memory_used,
			'status' => $this->status,
			'score' => $this->score,
			'ce' => $this->ce,
			'testcases' => $this->testcases,
			'sim_id' => $this->sim_id,
			'judged_at' => $this->judged_at,
			'judger_id' => $this->judger_id,
			'cnt_testcases' => $this->cnt_testcases,
			'created_at' => $this->created_at,
			'judger' => [
				'name' => isset($this->judger)?$this->judger->name:'',
			],
		];
	}

	public function testcaseByName(){
		if(!is_array($this->testcases)) return [];
		$testcases = [];
		foreach($this->testcases as $testcase){
			$testcases[$testcase->filename] = $testcase;
		}
		return $testcases;
	}

	public function calc_score(){
		if(!$this->problem->use_subtasks) return;
		$this->score = 0;
		$tot_score = 0;

		if(!is_array($this->problem->subtasks)) return;

		$testcases = $this->testcaseByName();

		foreach($this->problem->subtasks as $subtask){
			$tot_score += $subtask->score;
			$subtask_score = 100;
			foreach($subtask->testcases as $name){
				$tsc = 0;
				if(isset($testcases[$name])) $tsc = $testcases[$name]->score;
				switch($subtask->type){
					case "min":
						$subtask_score = min($subtask_score, $tsc);
						break;
					case "mul":
						$subtask_score *= $tsc / 100;
						break;
					case "ave":
						$subtask_score += $tsc;
						break;
				}
			}

			if($subtask->type == "ave") $subtask_score = ($subtask_score - 100) / count($subtask->testcases);

			$this->score += floor($subtask->score * ($subtask_score / 100));
		}

		if($tot_score != 100) $this->score = 0;
	}
}
