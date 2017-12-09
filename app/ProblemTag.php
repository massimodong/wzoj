<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemTag extends Model
{
	public function problems()
	{
		return $this->belongsToMany('App\Problem');
	}

	public function child_tags()
	{
		return $this->hasMany('App\ProblemTag', 'parent_id', 'id');
	}

	public function parent_tag()
	{
		return $this->belongsTo('App\ProblemTag', 'parent_id', 'id');
	}
}
