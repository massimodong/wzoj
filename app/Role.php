<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $fillable = ['name'];
	protected $casts = [
		'id' => 'integer',
	];

	public function users(){
		return $this->belongsToMany('App\User');
	}
}
