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
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
        'cookie' => \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        'session' => \Illuminate\Session\Middleware\StartSession::class,
        'session_errors' => \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        'antibot' => \App\Http\Middleware\BlockIfBot::class,
	
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
	'admin' => \App\Http\Middleware\VerifyIfAdmin::class,
	'judger'=> \App\Http\Middleware\VerifyIfJudger::class,
	'contest' => \App\Http\Middleware\OnlyContest::class,
	'forum' => \App\Http\Middleware\Forum::class,
	'role' => \App\Http\Middleware\Role::class,
	'single_session' => \App\Http\Middleware\SingleSession::class,
    ];
}
