<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\UserToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_token_id' => UserToken::factory(),
            'title' => $this->faker->title,
            'completed' => mt_rand(0, 1),
            'expired' => mt_rand(0, 1),
        ];
    }
}
