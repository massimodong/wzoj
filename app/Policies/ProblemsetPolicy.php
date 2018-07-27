<?php

namespace App\Policies;

use App\User;
use App\Problemset;

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

    public function view(User $user,Problemset $problemset){
	    if($problemset->public) return true;
	    foreach($user->problemsets() as $ps){
		    if($problemset->id == $ps->id) return true;
	    }
	    return false;
    }

    public function update(User $user, Problemset $problemset){
	    return $user->id === $problemset->manager_id;
    }

    public function create(User $user, $problemset){
	    return $user->has_role('problemset_creator');
    }

    public function view_tutorial(User $user, Problemset $problemset){
	    $flag = false;
	    if($problemset->public) $flag = true;
	    else{
		    foreach($user->problemsets() as $ps){
			    if($problemset->id == $ps->id){
				    $flag = true;
				    break;
			    }
		    }
	    }
	    if(!$flag) return false;

	    if($problemset->type == 'set') return true;
	    else{
		    if(time() > strtotime($problemset->contest_end_at)){
			    return true;
		    }
	    }

	    return false;
    }
}
