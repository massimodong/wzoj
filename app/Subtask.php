<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
	protected $guarded = ['id','created_at','updated_at'];
	protected $casts =[
		'id' => 'integer',
		'problem_id' => 'integer',
		'score' => 'integer',
		'testcases' => 'array',
		'calc_type' => 'integer',
	];

	public function problem(){
		return $this->belongsTo('App\Problem');
	}
}
