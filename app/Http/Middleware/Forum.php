<?php

namespace App\Http\Middleware;

use Closure;

class Forum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	if(ojoption('forum_enabled')){
		return $next($request);
	}else{
		abort(403);
	}
    }
}
