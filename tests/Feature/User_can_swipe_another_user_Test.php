<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\FactoryHelpers;

class User_can_swipe_another_user_Test extends TestCase
{
    use RefreshDatabase, FactoryHelpers;

    private ?User $user_1;
    private ?User $user_2;

    protected function setUp(): void
    {
        parent::setUp();

        // arrange part
        User::unguard();
        $this->user_1 = $this->createUser(['id' => 1]);
        $this->user_2 = $this->createUser(['id' => 2]);
    }

    /** @test */
    public function user_can_like_another_user(): void
    {
        // arrange
        $reaction = UserReaction::REACTION_SWIPE_LIKE;

        // act
        $response = $this->apiAddReaction($this->user_1, $this->user_2, $reaction);

        // assert
        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'from_user_id' => $this->user_1->getKey(),
                'to_user_id' => $this->user_2->getKey(),
                'type' => UserReaction::TYPE_SWIPE,
                'reaction' => $reaction,
            ]
        ]);
    }

    private function apiAddReaction(
        User $user_1,
        User $user_2,
        string $reaction
    ): \Illuminate\Testing\TestResponse {
        return $this->postJson('/api/reactions', [
            'from_user_id' => $user_1->getKey(),
            'to_user_id' => $user_2->getKey(),
            'reaction' => $reaction,
        ]);
    }

    /** @test */
    public function cannot_add_second_reaction_of_same_type(): void
    {
        // arrange
        $this->apiAddReaction($this->user_1, $this->user_2, UserReaction::REACTION_SWIPE_LIKE);

        // act & assert
        $this->apiAddReaction($this->user_1, $this->user_2, UserReaction::REACTION_SWIPE_LIKE)
            ->assertInvalid(['reaction'])
            ->assertStatus(422);
        $this->apiAddReaction($this->user_1, $this->user_2, UserReaction::REACTION_SWIPE_DISLIKE)
            ->assertInvalid(['reaction'])
            ->assertStatus(422);
    }
}
