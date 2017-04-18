<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumReply extends Model
{
	use SoftDeletes;
	protected $guarded = ['id'];
	public function user(){
		return $this->belongsTo('App\User');
	}

	public function topic(){
		return $this->belongsTo('App\ForumTopic', 'forum_topic_id');
	}
}
