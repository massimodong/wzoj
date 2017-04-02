<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\BlockIfBot::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
        'cookie' => \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        'session' => \Illuminate\Session\Middleware\StartSession::class,
        'session_errors' => \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
	
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
	'admin' => \App\Http\Middleware\VerifyIfAdmin::class,
	'judger'=> \App\Http\Middleware\VerifyIfJudger::class,
    ];
}
