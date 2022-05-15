<?php

namespace Tests\Unit\Models\User;

use App\Models\UserReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\FactoryHelpers;

class UserRelationsTest extends TestCase
{
    use RefreshDatabase, FactoryHelpers;

    /** @test */
    public function new_user_dont_have_any_reactions()
    {
        $user = $this->createUser();
        self::assertEmpty($user->reactions);
    }

    /** @test */
    public function user_can_have_reactions()
    {
        $user = $this->createUser();
        $user->reactions()->save(UserReaction::factory()->make());
        self::assertNotEmpty($user->reactions);
        self::assertEquals($user->id, $user->reactions->first()->from_user_id);
    }
}
