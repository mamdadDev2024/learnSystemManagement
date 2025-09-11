<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseResource;

class ShowCourseAction
{
    public function handle(Course $course)
    {
        $course = Course::with([
            "owner",
            "comments.user",
            "comments" => function ($query) {
                $query->latest()->limit(10);
            },
        ])
            ->withCount([
                "views",
                "likes",
                "comments",
                "enrollments",
                "enrollments as active_enrollments_count" => function ($query) {
                    $query->where("status", "active");
                },
            ])
            ->findOrFail($course->id);

        return new CourseResource($course);
    }
}
