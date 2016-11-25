<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
	use SoftDeletes;
	protected $guarded = ['id','created_at','updated_at','deleted_at'];
	protected $dates = ['deleted_at'];
}
