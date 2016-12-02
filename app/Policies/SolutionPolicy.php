<?php

namespace App\Policies;

use App\User;
use App\Solution;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolutionPolicy
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

    public function view(User $user,Solution $solution){
	    return $solution->user_id == $user->id;
    }
}
