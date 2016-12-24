<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
	protected $guarded = ["id", "created_at", "updated_at"];
	public function solution(){
		return $this->belongsTo('App\Solution');
	}
}
