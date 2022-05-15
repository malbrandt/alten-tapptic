<?php

namespace App\Services;

use App\Events\UserReactionAdded;
use App\Models\UserReaction;

class UserReactionService
{
    private UserReaction $userReaction;

    public function __construct(UserReaction $userReaction)
    {
        $this->userReaction = $userReaction;
    }

    public function add(int $fromUserId, int $toUserId, string $type, string $reaction): ?UserReaction
    {
        $exists = $this->userReaction->newModelQuery()
            ->where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('type', $type)
            ->exists();

        if ($exists) {
            return null;
        }

        $userReaction = $this->userReaction->newModelQuery()->create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'type' => $type,
            'reaction' => $reaction,
        ]);

        UserReactionAdded::dispatch($userReaction);

        return $userReaction;
    }
}
