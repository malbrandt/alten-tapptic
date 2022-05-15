<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserMatch;
use App\Models\UserReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\FactoryHelpers;

class Users_can_create_matches_Test extends TestCase
{
    use RefreshDatabase, FactoryHelpers;

    private ?User $user_1;
    private ?User $user_2;

    protected function setUp(): void
    {
        parent::setUp();

        // arrange part
        User::unguarded(function () {
            $this->user_1 = $this->createUser(['id' => 1]);
            $this->user_2 = $this->createUser(['id' => 2]);
        });
    }

    /** @test */
    public function users_can_create_matches_if_it_does_not_already_exists(): void
    {
        // act
        $this->postJson('/api/reactions', [
            'from_user_id' => $this->user_1->getKey(),
            'to_user_id' => $this->user_2->getKey(),
            'reaction' => UserReaction::REACTION_SWIPE_LIKE,
        ]);
        $this->postJson('/api/reactions', [
            'from_user_id' => $this->user_2->getKey(),
            'to_user_id' => $this->user_1->getKey(),
            'reaction' => UserReaction::REACTION_SWIPE_LIKE,
        ]);

        // assert
        $matchExists = UserMatch::query()
            ->where(function ($query) {
                $query->where('first_user_id', $this->user_1->getKey());
                $query->where('second_user_id', $this->user_2->getKey());
            })
            ->orWhere(function ($query) {
                $query->where('first_user_id', $this->user_2->getKey());
                $query->where('second_user_id', $this->user_1->getKey());
            })
            ->exists();

        self::assertTrue($matchExists);
    }
}
