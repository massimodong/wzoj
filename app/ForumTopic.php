<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ForumTopic extends Model
{
	protected $guarded = ['id'];
	public $timestamps = false;
	public function replies(){
		return $this->hasMany('App\ForumReply');
	}

	public function tags(){
		return $this->hasMany('App\ForumTag');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function reply($user, $content){
		DB::insert('INSERT INTO `forum_replies` (`user_id`, `forum_topic_id`, `index`, `content`,`created_at`,`updated_at`)
				SELECT ?, ?, IFNULL(MAX(`index`), 0)+1, ?, ?, ?
				FROM `forum_replies` WHERE `forum_topic_id` = ?',[
			$user->id,
			$this->id,
			$content,
			date('Y-m-d H:i:s'),
			date('Y-m-d H:i:s'),
			$this->id,
			]);
	}
}
