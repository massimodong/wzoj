<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualParticipation extends Model
{
  public $timestamps = false;
  public function user(){
    return $this->belongsTo('App\User');
  }
}
