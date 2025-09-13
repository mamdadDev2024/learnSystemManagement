<?php

namespace Modules\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Course\Models\Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->sentence(4),
            "slug" => $this->faker->slug(),
            "description" => $this->faker->paragraph(),
            "price" => $this->faker->randomFloat(2, 0, 1000),
            "published" => $this->faker->boolean(80), // 80% chance of being published
            "user_id" => UserFactory::class, // or User::inRandomOrder()->first()->id
        ];
    }
}
