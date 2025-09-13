<?php

namespace Modules\Lesson\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Lesson\Models\Lesson;
use Modules\Course\Models\Course;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $course = Course::inRandomOrder()->first() ?? Course::factory()->create();

        $maxOrder = Lesson::where('course_id', $course->id)->max('order') ?? 0;

        return [
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraph(),
            'order' => $maxOrder + 1,
            'duration' => $this->faker->numberBetween(5, 120),
            'is_published' => $this->faker->boolean(80), // 80% chance of being published
            'course_id' => $course->id,
            'attachment_url' => $this->faker->url(), // 30% chance of having attachment
            'attachment_name' => $this->faker->word() . '.pdf',
            'video_url' => $this->faker->url(), // 70% chance of having video
            'video_name' => $this->faker->words(2, true) . '.mp4',
        ];
    }

    /**
     * برای یک دوره خاص
     */
    public function forCourse(Course $course): static
    {
        return $this->state(function (array $attributes) use ($course) {
            $maxOrder = Lesson::where('course_id', $course->id)->max('order') ?? 0;

            return [
                'course_id' => $course->id,
                'order' => $maxOrder + 1,
            ];
        });
    }

    /**
     * با order خاص
     */
    public function withOrder(int $order): static
    {
        return $this->state([
            'order' => $order,
        ]);
    }

    /**
     * منتشر شده
     */
    public function published(): static
    {
        return $this->state([
            'is_published' => true,
        ]);
    }

    /**
     * منتشر نشده
     */
    public function unpublished(): static
    {
        return $this->state([
            'is_published' => false,
        ]);
    }

    /**
     * با ویدیو
     */
    public function withVideo(): static
    {
        return $this->state([
            'video_url' => $this->faker->url(),
            'video_name' => $this->faker->words(2, true) . '.mp4',
        ]);
    }

    /**
     * بدون ویدیو
     */
    public function withoutVideo(): static
    {
        return $this->state([
            'video_url' => null,
            'video_name' => null,
        ]);
    }

    /**
     * با فایل ضمیمه
     */
    public function withAttachment(): static
    {
        return $this->state([
            'attachment_url' => $this->faker->url(),
            'attachment_name' => $this->faker->word() . '.pdf',
        ]);
    }

    /**
     * بدون فایل ضمیمه
     */
    public function withoutAttachment(): static
    {
        return $this->state([
            'attachment_url' => null,
            'attachment_name' => null,
        ]);
    }
}
