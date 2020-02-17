<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function change_fullname(User $auth, User $user){
	    return ($auth->id == $user->id) && ($auth->fullname_lock == false);
    }

    public function change_class(User $auth, User $user){
	    return ($auth->id == $user->id) && ($auth->class_lock == false);
    }

    public function change_description(User $auth, User $user){
	    return $auth->id == $user->id;
    }

    public function change_avatar(User $auth, User $user){
      return $auth->id == $user->id;
    }

    public function view_files(User $auth, User $user){
	    return $auth->id == $user->id;
    }

    public function modify_files(User $auth, User $user){
	    return ($auth->id == $user->id) && ($auth->has_role('manager'));
    }

}
