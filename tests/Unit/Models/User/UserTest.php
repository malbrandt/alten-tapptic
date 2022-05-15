<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function user_has_factory()
    {
        self::assertInstanceOf(User::class, User::factory()->make());
    }
}
