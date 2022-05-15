<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserReaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(UserReaction::TYPES);
        return [
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'type' => $type,
            'reaction' => $this->faker->randomElement(UserReaction::TYPE_REACTIONS[$type]),
        ];
    }
}
