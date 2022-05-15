<?php

namespace Tests\Services;

use App\Events\UserReactionAdded;
use App\Models\UserReaction;
use App\Services\UserReactionService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class UserReactionServiceTest extends TestCase
{
    /** @test */
    public function user_can_not_reaction_of_same_type_twice(): void
    {
        Event::fake();

        $userReactionMock = $this->mockModel(UserReaction::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->andReturn(true);
        });

        $service = new UserReactionService($userReactionMock);

        self::assertNull($service->add(1, 2, UserReaction::TYPE_SWIPE, UserReaction::REACTION_SWIPE_LIKE));
        Event::assertNothingDispatched();
    }

    /** @test */
    public function user_can_react_to_another_user(): void
    {
        Event::fake();

        $userReactionMock = $this->mockModel(
            UserReaction::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('exists')->andReturn(false);
                $mock->shouldReceive('create')->andReturn(new UserReaction([
                    'from_user_id' => 1,
                    'to_user_id' => 2,
                    'type' => UserReaction::TYPE_SWIPE,
                    'reaction' => UserReaction::REACTION_SWIPE_LIKE,
                ]));
            }
        );

        $service = new UserReactionService($userReactionMock);
        $reaction = $service->add(1, 2, UserReaction::TYPE_SWIPE, UserReaction::REACTION_SWIPE_LIKE);

        self::assertEquals($reaction->from_user_id, 1);
        self::assertEquals($reaction->to_user_id, 2);
        self::assertEquals($reaction->type, UserReaction::TYPE_SWIPE);
        self::assertEquals($reaction->reaction, UserReaction::REACTION_SWIPE_LIKE);

        Event::assertDispatched(UserReactionAdded::class);
    }
}
