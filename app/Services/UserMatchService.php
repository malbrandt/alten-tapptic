<?php

namespace App\Services;

use App\Events\PairMatched;
use App\Exceptions\BusinessLogicValidationException;
use App\Models\UserMatch;
use App\Models\UserReaction;
use Illuminate\Database\Eloquent\Builder;

class UserMatchService
{
    private UserReaction $userReaction;
    private UserMatch $userMatch;

    public function __construct(UserReaction $userReaction, UserMatch $userMatch)
    {
        $this->userReaction = $userReaction;
        $this->userMatch = $userMatch;
    }

    public function checkLikeIsReciprocated(UserReaction $userReaction): bool
    {
        if ($userReaction->type !== UserReaction::TYPE_SWIPE
            || $userReaction->reaction !== UserReaction::REACTION_SWIPE_LIKE) {
            return false;
        }

        return $this->userReaction->newModelQuery()
            ->where('from_user_id', $userReaction->to_user_id)
            ->where('to_user_id', $userReaction->from_user_id)
            ->where('type', UserReaction::TYPE_SWIPE)
            ->where('reaction', UserReaction::REACTION_SWIPE_LIKE)
            ->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|UserMatch
     */
    public function createMatch(int $firstUserId, int $secondUserId): UserMatch
    {
        if ($this->matchExists($firstUserId, $secondUserId)) {
            throw new BusinessLogicValidationException('reaction', \sprintf(
                    'Match between users #%d and #%d already exists!',
                    $firstUserId,
                    $secondUserId
                )
            );
        }

        $match = $this->userMatch->newModelQuery()->create([
            'first_user_id' => $firstUserId,
            'second_user_id' => $secondUserId,
        ]);

        PairMatched::dispatch($firstUserId, $secondUserId);

        return $match;
    }

    private function matchExists(int $firstUserId, int $secondUserId): bool
    {
        return $this->userMatch->newModelQuery()
            ->where(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('first_user_id', $firstUserId);
                $query->orWhere('second_user_id', $secondUserId);
            })
            ->orWhere(function (Builder $query) use ($firstUserId, $secondUserId) {
                $query->where('first_user_id', $secondUserId);
                $query->orWhere('second_user_id', $firstUserId);
            })->exists();
    }
}
