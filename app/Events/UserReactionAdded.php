<?php

namespace App\Events;

use App\Models\UserReaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserReactionAdded
{
    use Dispatchable, SerializesModels;

    public UserReaction $userReaction;

    public function __construct(UserReaction $userReaction)
    {
        $this->userReaction = $userReaction;
    }
}
