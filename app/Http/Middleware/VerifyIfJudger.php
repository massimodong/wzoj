<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Judger;

class VerifyIfJudger
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
	    $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if(!isset($request->judger_token)){
		    return response('Need Token', 401);
	    }
	    $judger = Judger::where('token', $request->judger_token)->first();
	    if($judger == NULL){
		    return response('Invalid Token', 401);
	    }
	    $request->attributes->add(['judger' => $judger]);
	    return $next($request);
    }
}
