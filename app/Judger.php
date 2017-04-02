<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Judger extends Model
{
	public function solutions(){
		return $this->hasMany('App\Solution');
	}
}
