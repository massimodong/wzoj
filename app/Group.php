<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	public function users(){
		return $this->belongsToMany('App\User');
	}

	public function invitations(){
		return $this->belongsToMany('App\Invitation');
	}
}
