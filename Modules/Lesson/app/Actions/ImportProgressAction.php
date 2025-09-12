<?php

namespace Modules\Lesson\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportProgressAction
{
    public function handle(Lesson $lesson, array $data)
    {
        return DB::transaction(function () use ($lesson, $data) {
            $user = auth("sanctum")->user();

            if (
                !$lesson->course
                    ->students()
                    ->where("user_id", $user->id)
                    ->exists()
            ) {
                throw new \Exception(
                    "User does not have access to this course",
                );
            }

            $progress = $lesson->progress()->updateOrCreate(
                [
                    "user_id" => $user->id,
                    "lesson_id" => $lesson->id,
                ],
                [
                    "percentage" => $data["percentage"],
                    "started_at" => $data["started_at"],
                    "completed_at" => $data["completed_at"] ?? null,
                    "time_spent" => $data["time_spent"] ?? 0,
                    "status" => $this->determineStatus(
                        $data["percentage"],
                        $data["completed_at"] ?? null,
                    ),
                    "notes" => $data["notes"] ?? null,
                ],
            );

            if ($data["percentage"] == 100 && !$progress->completed_at) {
                $progress->update(["completed_at" => now()]);
            }

            $this->updateCourseProgress($lesson->course, $user);

            return $progress->load("user", "lesson");
        });
    }

    protected function determineStatus(
        float $percentage,
        ?string $completedAt,
    ): string {
        if ($percentage == 100 || $completedAt) {
            return "completed";
        } elseif ($percentage > 0) {
            return "in_progress";
        } else {
            return "not_started";
        }
    }

    protected function updateCourseProgress($course, $user): void
    {
        $totalLessons = $course->lessons()->count();
        $completedLessons = $course
            ->lessons()
            ->whereHas("progress", function ($query) use ($user) {
                $query->where("user_id", $user->id)->where("percentage", 100);
            })
            ->count();

        $overallProgress =
            $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        $course->progress()->updateOrCreate(
            ["user_id" => $user->id],
            [
                "percentage" => $overallProgress,
                "completed_lessons" => $completedLessons,
                "total_lessons" => $totalLessons,
                "last_activity_at" => now(),
            ],
        );
    }
}
