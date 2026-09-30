<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function manage(User $user, Event $event): bool
    {
        return $user->isAdmin() || $event->organizer_id === $user->id;
    }

    public function update(User $user, Event $event): bool
    {
        return $this->manage($user, $event);
    }

    public function delete(User $user, Event $event): bool
    {
        return $this->manage($user, $event);
    }
}
