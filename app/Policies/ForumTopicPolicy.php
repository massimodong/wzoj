<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\ForumTopic;

class ForumTopicPolicy
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

    public function update(User $user, ForumTopic $topic){
	    return $user->id === $topic->user_id;
    }

    public function delete(User $user, ForumTopic $topic){
	    return $user->id === $topic->user_id;
    }
}
