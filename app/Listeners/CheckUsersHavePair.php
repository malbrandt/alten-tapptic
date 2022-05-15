<?php

namespace App\Listeners;

use App\Events\UserReactionAdded;
use App\Services\UserMatchService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckUsersHavePair implements ShouldQueue
{
    private UserMatchService $userMatchService;

    public function __construct(UserMatchService $userMatchService)
    {
        $this->userMatchService = $userMatchService;
    }

    public function handle(UserReactionAdded $event): void
    {
        if ($this->userMatchService->checkLikeIsReciprocated($event->userReaction)) {
            $this->userMatchService->createMatch(
                $event->userReaction->from_user_id,
                $event->userReaction->to_user_id
            );
        }
    }
}
