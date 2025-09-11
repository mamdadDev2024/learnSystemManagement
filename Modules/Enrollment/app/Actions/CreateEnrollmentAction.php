<?php

namespace Modules\Enrollment\Actions;

use Modules\Course\Models\Course;

class CreateEnrollmentAction
{
    public function handle(Course $course)
    {
        return auth("sanctum")
            ->user()
            ->enrollments()
            ->create([
                "course_id" => $course->id,
            ]);
    }
}
