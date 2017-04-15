<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemTag extends Model
{
	public function problems()
	{
		return $this->belongsToMany('App\Problem');
	}
}
