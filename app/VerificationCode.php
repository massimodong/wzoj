<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'task', 'verified', 'params', 'targets'];

    protected $casts = [
      "params" => "array",
      "targets" => "array",
    ];

    public function user(){
      return $this->belongsTo(\App\User);
    }
}
