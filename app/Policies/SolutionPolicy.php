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
	    return true;
	    //return $solution->user_id == $user->id;
    }

    public function judge(User $user,$solution){
	    if($user->has_role('judger')){
		    return true;
	    }
    }

    public function view_code(User $user, Solution $solution){
	    return $solution->user_id == $user->id;
    }
}
