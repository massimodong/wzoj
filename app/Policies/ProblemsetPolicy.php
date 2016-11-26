<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ProblemsetPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user,$ability){
	    if($user->has_role('admin')){
		    return true;
	    }
    }
}
