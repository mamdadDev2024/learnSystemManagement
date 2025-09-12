<?php

// Modules/Lesson/Database/Factories/LessonFactory.php
namespace Modules\Lesson\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Lesson\Models\Lesson;
use Modules\Course\Models\Course;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'order' => $this->faker->numberBetween(1, 20),
            'duration' => $this->faker->numberBetween(5, 120),
            'is_published' => $this->faker->boolean(80), // 80% chance of being published
            'course_id' => Course::factory(),
        ];
    }
}