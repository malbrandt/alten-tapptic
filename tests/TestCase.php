<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            ThrottleRequests::class,
        ]);
    }

    protected function mockModel(string $abstract, ?callable $callback = null): MockInterface
    {
        $mock = $this->mock($abstract, function (MockInterface $mock) {
            $mock->shouldReceive('newModelQuery')->andReturn($mock);
            $mock->shouldReceive('where')->andReturn($mock);
            $mock->shouldReceive('orWhere')->andReturn($mock);
        });

        if (is_callable($callback)) {
            $callback($mock);
        }

        return $mock;
    }
}
