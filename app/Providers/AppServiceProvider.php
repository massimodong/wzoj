<?php

namespace App\Providers;

use Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Password::defaults(function () {
        return Password::min(8)
                       ->mixedCase()
                       ->uncompromised();
      });
	    //check if the invitation is available
	    Validator::extend('invitation',function($attribute, $value, $parameters, $validator){
			    $invitation = \App\Invitation::where('token',$value)->first();
			    if($invitation == NULL) return false;
			    return $invitation->remaining != 0;
	    });

	    //check if valid username
	    Validator::extend('username',function($attribute, $value, $parameters, $validator){
			    $length = strlen($value);
			    if($length < 3) return false;
			    if($length > 31) return false;
			    $valid_chs = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_");
			    $numbers = str_split("1234567890");
			    for($i=0;$i<$length;++$i){
			        if(!in_array($value[$i], $valid_chs)) return false;
			    }
			    if(in_array($value[0], $numbers)) return false;
			    return true;
	    });

      Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    //require the app/Http/Helpers.php
	    require_once base_path().'/app/Http/Helpers.php';
    }
}
