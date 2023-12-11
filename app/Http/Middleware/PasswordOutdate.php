<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;

class PasswordOutdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
          if(Auth::user()->has_role('admin')) return $next($request);
          if(Auth::user()->is_pwd_outdate){
            if(!$request->is('password/change') && !$request->is('auth/logout')){
              return redirect('/password/change')->withErrors([trans('wzoj.password_outdate')]);
            }
          }
        }
        return $next($request);
    }
}
