<?php

namespace Modules\Enrollment\Actions;

use Modules\Course\Models\Course;

class CheckEnrollmentAction
{
    public function handle(Course $course): bool
    {
        return auth("sanctum")->user()->checkEnrollment($course);
    }
}
