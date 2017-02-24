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

	public function scopeOrderByIndex($query){
		return $query->orderBy('problem_problemset.index','asc');
	}
}
