<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BlockIfBot
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
	    if(!(Auth::check())) return $next($request);
	    $user = Auth::user();
	    if($user->bot_tendency >= 1000){ //Definitely a bot, ban user
		    Auth::logout();
        if($request->ajax()){
          return response()->json(['msg' => trans('wzoj.blocked'), 'err_code' => 'blocked'], 422);
        }else{
          return redirect('/');
        }
	    }else if($user->bot_tendency >= 100){ //block request
		    if($request->path() === 'sorry' || $request->path() === '_captcha/default') return $next($request);
		    else{
          if($request->ajax()){
            return response()->json(['msg' => trans('wzoj.blocked'), 'err_code' => 'blocked'], 422);
          }else{
            $user->isbot(10);
            return redirect('/sorry');
          }
		    }
	    }
	    return $next($request);
    }
}
