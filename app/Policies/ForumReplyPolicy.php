<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\ForumTopic;
use App\ForumReply;

class ForumReplyPolicy
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

    public function update(User $user, ForumReply $reply){
	    return $user->id === $reply->user_id;
    }

    public function delete(User $user, ForumReply $reply){
	    return $user->id === $reply->user_id ||
		    $user->id === $reply->topic->user_id;
    }
}
