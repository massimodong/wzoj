<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumTag extends Model
{
	protected $guarded = ['id'];
	public function topic(){
		return $this->belongsTo('App\ForumTopic');
	}
}
