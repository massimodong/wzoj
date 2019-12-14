<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class SingleSession
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
        if(Auth::check()){
          if(Auth::user()->last_token != Session::getId()){
            Auth::logout();
            return redirect("/auth/login");
          }
        }
        return $next($request);
    }
}
