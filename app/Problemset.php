<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problemset extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function problems(){
		return $this->belongsToMany('App\Problem')->withPivot('index');
	}
}
