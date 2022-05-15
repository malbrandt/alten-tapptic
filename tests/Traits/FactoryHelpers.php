<?php

namespace Tests\Traits;

use App\Models\User;

trait FactoryHelpers
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|User
     */
    protected function createUser()
    {
        return User::factory()->create();
    }
}
