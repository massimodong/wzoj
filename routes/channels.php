<?php

Broadcast::channel('user.{user_id}', function ($user, $user_id) {
    return $user->id === intval($user_id);
});
