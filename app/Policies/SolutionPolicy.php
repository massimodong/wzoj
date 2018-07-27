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

    public function view_code(User $user, Solution $solution){
	    if($solution->user_id == $user->id) return true;
	    if($user->has_role('code_viewer')) return true;
	    if($user->has_role('problem_manager') && $solution->problem->manager_id == $user->id) return true;
	    if($user->has_role('problemset_manager') && $solution->problemset->manager_id == $user->id) return true;
	    return false;
    }
}
