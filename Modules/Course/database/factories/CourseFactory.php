<?php

namespace Modules\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
        $title = fake()->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'price' => rand(1000 , 10000),
            'description' => fake()->text(400),
            'published' => (bool) rand(0,1)
        ];
    }
}

