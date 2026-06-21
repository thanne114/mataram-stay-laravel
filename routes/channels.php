<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conversation = \App\Models\Conversation::find($id);
    if (!$conversation) {
        return false;
    }
    return (int) $user->id === (int) $conversation->seeker_id || (int) $user->id === (int) $conversation->owner_id;
});
