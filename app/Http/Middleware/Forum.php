<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
		if(Auth::check() && Auth::user()->has_role('admin')){
			echo trans('wzoj.msg_forum_disabled');
			return $next($request);
		}else{
			abort(403);
		}
	}
    }
}
