<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
	protected $guarded = ['id'];
	public function user(){
		return $this->belongsTo('App\User');
	}

	public function topic(){
		return $this->belongsTo('App\ForumTopic');
	}
}
