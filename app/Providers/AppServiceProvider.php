<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    //check if the invitation is available
	    Validator::extend('invitation',function($attribute, $value, $parameters, $validator){
			    $invitation = \App\Invitation::where('token',$value)->first();
			    if($invitation == NULL) return false;
			    return $invitation->remaining != 0;
	    });

	    $this->publishes([
			    'vendor/twbs/bootstrap' => public_path('vendor/bootstrap'),
			    'vendor/tinymce/tinymce' => public_path('vendor/tinymce'),
	    ],'wzoj');
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
