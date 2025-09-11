<?php

namespace Modules\Enrollment\Actions;

use Modules\Course\Models\Course;

class DeleteEnrollmentAction
{
    public function handle(Course $course)
    {
        return auth("sanctum")
            ->user()
            ->enrollments()
            ->where("course_id", $course->id)
            ->delete();
    }
}
