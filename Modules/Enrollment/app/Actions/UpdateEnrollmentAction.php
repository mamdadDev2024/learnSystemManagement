<?php

namespace Modules\Enrollment\Actions;

use Modules\Course\Models\Course;
use Modules\Enrollment\Enums\EnrollmentStatus;

class UpdateEnrollmentAction
{
    public function handle(Course $course, $status)
    {
        return auth("sanctum")
            ->user()
            ->enrollments()
            ->where("course_id", $course->id)
            ->update([
                "status" => EnrollmentStatus::tryFrom($status)->value,
            ]);
    }
}
