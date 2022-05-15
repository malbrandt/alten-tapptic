<?php

namespace App\Services;

use App\Events\UserReactionAdded;
use App\Exceptions\BusinessLogicValidationException;
use App\Models\UserReaction;

class UserReactionService
{
    private UserReaction $userReaction;

    public function __construct(UserReaction $userReaction)
    {
        $this->userReaction = $userReaction;
    }

    public function add(int $fromUserId, int $toUserId, string $type, string $reaction): UserReaction
    {
        // Prevent from adding reaction of same type between same two users
        if ($this->checkExists($fromUserId, $toUserId, $type)) {
            throw new BusinessLogicValidationException('reaction', 'Reactions to users cannot be changed.');
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

    public function checkExists(int $fromUserId, int $toUserId, string $type): bool
    {
        return $this->userReaction->newModelQuery()
            ->where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('type', $type)
            ->exists();
    }
}
