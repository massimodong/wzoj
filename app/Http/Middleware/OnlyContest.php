<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class OnlyContest
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
	    if(Auth::check() && Auth::user()->has_role('admin')){
		    return $next($request);
	    }

	    if(empty(ojoption('contest_problemsets'))){
		    return $next($request);
	    }

	    $paths = explode('/', $request->path(), 3);
	    $path = $paths[0];
	    $inBlackList = in_array($path, array(
			'ranklist',
			'users',
			'problem-search',
			'forum',
				    ));
	    if($inBlackList){
		    abort(403);
	    }

	    //available contests
	    $contests = json_decode('['.ojoption('contest_problemsets').']');
	    $request->attributes->add(['contests' => $contests]);

	    if($path == 's'){
		    if(!isset($paths[1])){
			    return redirect('/contests');
		    }
		    if(!in_array(intval($paths[1]), $contests)){
			    abort(403);
		    }
	    }

	    return $next($request);
    }
}
