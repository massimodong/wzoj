<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Problem;

class ProblemPolicy
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

    public function before($user, $problem){
	    if($user->has_role('admin')) return true;
    }

    public function create(User $user){
	    return $user->has_role('problem_creator');
    }

    public function manage(User $user, Problem $problem){
	    return $user->has_role('problem_manager') && ($user->id === $problem->manager_id);
    }
}
