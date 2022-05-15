<?php

namespace Tests\Unit\Models;

use App\Models\UserMatch;
use App\Models\UserReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\FactoryHelpers;

class UserTest extends TestCase
{
    use RefreshDatabase, FactoryHelpers;

    /** @test */
    public function new_user_dont_have_any_reactions(): void
    {
        self::assertEmpty($this->createUser()->reactions);
    }

    /** @test */
    public function user_can_have_reactions(): void
    {
        $user = $this->createUser();

        $user->reactions()->save(UserReaction::factory()->make());

        self::assertNotEmpty($user->reactions);
        self::assertEquals($user->id, $user->reactions->first()->from_user_id);
    }
}
