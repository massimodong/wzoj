<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $fillable = ['description','fullname','class','remaining','token','private'];
	protected $casts = [
		'id' => 'integer',
		'remaining' => 'integer',
	];

	public function groups(){
		return $this->belongsToMany('App\Group');
	}
}
