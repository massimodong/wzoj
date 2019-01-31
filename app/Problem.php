<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
	use SoftDeletes;
	protected $guarded = ['id','created_at','updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
	protected $casts = [
		'id' => 'integer',
		'type' => 'integer',
		'spj' => 'boolean',
		'timelimit' => 'integer',
		'memorylimit' => 'double',
	];

	public function problemsets(){
		return $this->belongsToMany('App\Problemset')->withPivot('index');
	}

	public function solutions(){
		return $this->hasMany('App\Solution');
	}

	public function tags(){
		return $this->belongsToMany('App\ProblemTag');
	}

	public function manager(){
		return $this->belongsTo('App\User');
	}

	public function update_cnt_submit(){
		$this->cnt_submit = $this->solutions()
			->count();
		$this->save();
	}

	public function update_cnt_ac(){
		$this->cnt_ac = $this->solutions()
			->where('score', '>=', 100)
			->count();
		$this->save();
	}

	public function scopeOrderByIndex($query){
		return $query->orderBy('problem_problemset.index','asc');
	}

	public function subtasks(){
		return $this->hasMany('App\Subtask');
	}
}
