<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;

class Problemset extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name','type','public','description','contest_start_at','contest_end_at', 'tag', 'remark', 'show_problem_tags', 'contest_hide_solutions', 'manager_id'];
	protected $casts = [
		'id' => 'integer',
		'public' => 'boolean',
	];

	public function problems(){
		return $this->belongsToMany('App\Problem')->withPivot('index');
	}

	public function groups(){
		return $this->belongsToMany('App\Group');
	}

	public function solutions(){
		return $this->hasMany('App\Solution');
	}

	public function manager(){
		return $this->belongsTo('App\User');
	}

	public function isContestRunning(){
		return ($this->type != 'set')  && (time() <= strtotime($this->contest_end_at));
	}

	public function isHideSolutions(){
		if(!$this->contest_hide_solutions) return false;
		if(Auth::check() && Auth::user()->has_role('admin')) return false;
		return $this->isContestRunning() && ($this->type != 'acm');
	}
}
