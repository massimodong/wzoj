<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $fillable = ['name'];

	/**
	 * Get the user who owns the file
	 */
	public function user(){
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the storing path of the file
	 */

	public function getPath(){
		return '/'.$this->user_id.'/'.$this->name;
	}

	/**
	 * Get the url of this image
	 */

	public function getUrl(){
		return '/files'.$this->getPath();
	}
}
