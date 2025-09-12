<?php

namespace Modules\Progress\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Lesson\Models\LessonProgress;
use Modules\User\Models\User;
use Modules\Lesson\Models\Lesson;

class LessonProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = LessonProgress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'is_completed' => $this->faker->boolean(30), 
            'progress_percentage' => $this->faker->numberBetween(0, 100),
            'started_at' => $this->faker->optional(70)->dateTimeBetween('-1 month', 'now'),
            'completed_at' => function (array $attributes) {
                return $attributes['is_completed'] 
                    ? $this->faker->dateTimeBetween($attributes['started_at'] ?? '-1 week', 'now')
                    : null;
            },
            'time_spent' => $this->faker->numberBetween(0, 3600),
            'last_accessed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the progress is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => true,
                'progress_percentage' => 100,
                'completed_at' => $attributes['started_at'] 
                    ? $this->faker->dateTimeBetween($attributes['started_at'], 'now')
                    : now(),
            ];
        });
    }

    /**
     * Indicate that the progress is not completed.
     */
    public function notCompleted(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => false,
                'progress_percentage' => $this->faker->numberBetween(0, 99),
                'completed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the progress is just started.
     */
    public function justStarted(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => false,
                'progress_percentage' => $this->faker->numberBetween(0, 20),
                'started_at' => now(),
                'last_accessed_at' => now(),
                'time_spent' => $this->faker->numberBetween(0, 300),
                'completed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the progress is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => false,
                'progress_percentage' => $this->faker->numberBetween(21, 99),
                'time_spent' => $this->faker->numberBetween(300, 1800),
            ];
        });
    }

    /**
     * Set specific progress percentage.
     */
    public function withPercentage(int $percentage): static
    {
        return $this->state(function (array $attributes) use ($percentage) {
            return [
                'progress_percentage' => max(0, min(100, $percentage)),
                'is_completed' => $percentage === 100,
                'completed_at' => $percentage === 100 
                    ? ($attributes['started_at'] ?? now())
                    : null,
            ];
        });
    }

    /**
     * Set specific time spent.
     */
    public function withTimeSpent(int $seconds): static
    {
        return $this->state([
            'time_spent' => max(0, $seconds),
        ]);
    }

    /**
     * Set specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    /**
     * Set specific lesson.
     */
    public function forLesson(Lesson $lesson): static
    {
        return $this->state([
            'lesson_id' => $lesson->id,
        ]);
    }

    /**
     * Set specific course (random lesson from that course).
     */
    public function forCourse($course): static
    {
        return $this->state(function (array $attributes) use ($course) {
            $lesson = Lesson::where('course_id', $course->id)->inRandomOrder()->first();
            
            return [
                'lesson_id' => $lesson?->id ?? Lesson::factory()->create(['course_id' => $course->id])->id,
            ];
        });
    }
}