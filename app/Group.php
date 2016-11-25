<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function users(){
		return $this->belongsToMany('App\User');
	}

	public function invitations(){
		return $this->belongsToMany('App\Invitation');
	}
}
