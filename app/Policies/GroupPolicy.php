<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Group;
use Cache;

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

	    $my_groups = Cache::tags(['user_groups'])->rememberForever($user->id, function() use($user){
		    return $user->groups;
	    });

	    foreach($my_groups as $g){
		    if($g->id === $group->id) return true;
	    }

	    return false;

    }

    public function manage(User $user, Group $group){
	    return $user->has_role('group_manager') && ($user->id === $group->manager_id);
    }
}
