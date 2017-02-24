<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
	protected $guarded = ["id", "created_at", "updated_at"];
	protected $casts = [
		'id' => 'integer',
		'solution_id' => 'integer',
		'time_used' => 'integer',
		'memory_used' => 'double',
		'score' => 'integer',
	];
	public function solution(){
		return $this->belongsTo('App\Solution');
	}
}
