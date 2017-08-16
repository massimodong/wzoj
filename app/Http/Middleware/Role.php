<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Role
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
    public function handle($request, Closure $next, $role)
    {
	    if ($this->auth->guest()) {
		    if ($request->ajax()) {
			    return response('Unauthorized.', 401);
		    } else {
			    return redirect()->guest('auth/login');
		    }
	    }

	    if($this->auth->user()->has_role($role)){
	    	return $next($request);
	    }else{
		return response('Unauthorized.', 401);
	    }

    }
}
