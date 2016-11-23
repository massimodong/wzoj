<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $fillable = ['description','fullname','class','remaining','token','private'];
}
