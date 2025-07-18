<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('Illuminate\Auth\Events\Registered', function ($data) {
          logAction('registered', $data->user, LOG_MODERATE, $data->user->id);
        });

        Event::listen('Illuminate\Auth\Events\Login', function ($data) {
          logAction('login', [], LOG_NORMAL);
        });

        Event::listen('Illuminate\Auth\Events\Failed', function ($data) {
          $name = $data->credentials["name"];
          $user = \App\User::where('name', $name)->first();
          $logdata = [
            "name" => $name,
          ];
          if(is_null($user)) logAction('failed_login', $logdata, LOG_MODERATE);
          else logAction('failed_login', $logdata, LOG_MODERATE, $user->id);
        });

        Event::listen('Illuminate\Auth\Events\Logout', function ($data) {
          logAction('logout', [], LOG_NORMAL);
        });

        Event::listen('Illuminate\Auth\Events\PasswordReset', function ($data) {
          logAction('password_reset', $data->user, LOG_SEVERE, $data->user->id);
        });

        //
    }
}
