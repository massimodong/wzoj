<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problemset extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name','type','public','description','contest_start_at','contest_end_at'];

	public function problems(){
		return $this->belongsToMany('App\Problem')->withPivot('index');
	}

	public function groups(){
		return $this->belongsToMany('App\Group');
	}

	public function solutions(){
		return $this->hasMany('App\Solution');
	}
}
