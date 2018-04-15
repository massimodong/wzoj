<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Group;

class GroupPolicy
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

    public function before($user, $group){
	    if($user->has_role('admin')) return true;
    }

    public function view($user, $group){
	    if($user->has_role('group_manager') && ($user->id === $group->manager_id)) return true;
    }

    public function manage(User $user, Group $group){
	    return $user->has_role('group_manager') && ($user->id === $group->manager_id);
    }
}
