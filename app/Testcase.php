<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
	public function solution(){
		return $this->belongsTo('App\Solution');
	}
}
