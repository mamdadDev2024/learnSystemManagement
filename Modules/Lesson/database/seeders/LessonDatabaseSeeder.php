<?php

namespace Modules\Lesson\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Models\Course;
use Modules\Lesson\Database\Factories\LessonFactory;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Modules\Lesson\Models\Progress;

class LessonDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $course = Course::factory()->create();
            $courses = collect([$course]);
            $this->command->info("one course created!");
        }

        foreach ($courses as $course) {
            $lessonCount = rand(5, 12);

            LessonFactory::new()
                ->count($lessonCount)
                ->forCourse($course)
                ->create();

            $this->command->info(
                "✅ {$lessonCount} lessons created for '{$course->title}' course",
            );
        }

        $this->createProgressData();

        $this->command->info("create fake lessons successfully!");
    }

    private function createProgressData(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn("nothing user not found!");
            return;
        }

        $lessons = Lesson::all();

        if ($lessons->isEmpty()) {
            $this->command->warn("nothing lesson not found!");
            return;
        }

        $progressCount = 0;

        foreach ($users as $user) {
            $userLessons = $lessons->random(rand(3, min(8, $lessons->count())));

            foreach ($userLessons as $lesson) {
                $isCompleted = rand(0, 1);

                Progress::create([
                    "user_id" => $user->id,
                    "lesson_id" => $lesson->id,
                    "is_completed" => $isCompleted,
                    "progress_percentage" => $isCompleted ? 100 : rand(10, 95),
                    "started_at" => now()->subDays(rand(1, 30)),
                    "completed_at" => $isCompleted
                        ? now()->subDays(rand(0, 15))
                        : null,
                    "time_spent" => rand(60, 3600),
                    "last_accessed_at" => now()->subDays(rand(0, 7)),
                ]);

                $progressCount++;
            }
        }

        $this->command->info("✅ {$progressCount} lesson progress created!");
    }
}
