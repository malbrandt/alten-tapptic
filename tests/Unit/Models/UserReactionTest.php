<?php

namespace Tests\Unit\Models;

use App\Models\UserReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserReactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_reaction_has_factory(): void
    {
        self::assertInstanceOf(UserReaction::class, UserReaction::factory()->make());
    }

    /** @test */
    public function can_set_only_correct_type(): void
    {
        $reaction = new UserReaction();
        self::assertEmpty($reaction->type);

        $reaction->type = UserReaction::TYPE_SWIPE;
        self::assertEquals(UserReaction::TYPE_SWIPE, $reaction->type);

        self::expectException(\InvalidArgumentException::class);
        $reaction = new UserReaction();
        $reaction->type = 'non_existing_type';
        self::assertEmpty($reaction->type);
    }

    /** @test */
    public function can_set_only_correct_reaction_when_type_is_given(): void
    {
        $reaction = new UserReaction(['type' => UserReaction::TYPE_SWIPE]);
        self::assertEmpty($reaction->reaction);

        $reaction->reaction = UserReaction::REACTION_SWIPE_LIKE;
        self::assertEquals(UserReaction::REACTION_SWIPE_LIKE, $reaction->reaction);

        self::expectException(\InvalidArgumentException::class);
        $reaction->reaction = 'non_existing_reaction';
    }

    /** @test */
    public function cannot_set_reaction_without_settings_type()
    {
        $reaction = new UserReaction();

        self::expectException(\LogicException::class);
        $reaction->reaction = UserReaction::REACTION_SWIPE_LIKE;

        self::assertNull($reaction->reaction);
    }
}
