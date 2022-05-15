<?php

namespace Tests\Services;

use App\Events\PairMatched;
use App\Exceptions\BusinessLogicValidationException;
use App\Models\UserMatch;
use App\Models\UserReaction;
use App\Services\UserMatchService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class UserMatchServiceTest extends TestCase
{
    /** @test */
    public function checks_if_like_is_reciprocated(): void
    {
        // arrange
        $userReactionModel = $this->mockModel(UserReaction::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->andReturn(true);
        });
        $userMatchModel = $this->mockModel(UserMatch::class);

        $service = new UserMatchService($userReactionModel, $userMatchModel);

        // act & assert
        self::assertTrue($service->checkLikeIsReciprocated(new UserReaction([
            'type' => UserReaction::TYPE_SWIPE,
            'reaction' => UserReaction::REACTION_SWIPE_LIKE,
        ])));
        self::assertFalse($service->checkLikeIsReciprocated(new UserReaction([
            'type' => UserReaction::TYPE_SWIPE,
            'reaction' => UserReaction::REACTION_SWIPE_DISLIKE,
        ])));
    }

    /** @test */
    public function creates_match_if_it_does_not_exists(): void
    {
        // arrange
        Event::fake();

        $userReactionModel = $this->mockModel(UserReaction::class);
        $userMatchModel = $this->mockModel(UserMatch::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->andReturn(false);
            $mock->shouldReceive('create')->once()->andReturn(new UserMatch([
                'first_user_id' => 1,
                'second_user_id' => 2,
            ]));
        });
        $service = new UserMatchService($userReactionModel, $userMatchModel);

        // act
        $match = $service->createMatch(1, 2);

        // assert
        self::assertEquals(1, $match->first_user_id);
        self::assertEquals(2, $match->second_user_id);
        Event::assertDispatched(PairMatched::class, function (PairMatched $event) {
            return $event->firstUserId === 1 && $event->secondUserId === 2;
        });
    }

    /** @test */
    public function does_not_create_match_if_it_already_exists(): void
    {
        Event::fake();

        $userReactionModel = $this->mockModel(UserReaction::class);
        $userMatchModel = $this->mockModel(UserMatch::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')->andReturn(true);
        });

        $service = new UserMatchService($userReactionModel, $userMatchModel);

        self::withoutExceptionHandling();
        self::expectException(BusinessLogicValidationException::class);
        $service->createMatch(1, 2);
    }
}
