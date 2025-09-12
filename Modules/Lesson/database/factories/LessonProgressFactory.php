<?php

namespace Modules\Lesson\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LessonProgressFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Lesson\Models\LessonProgress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

