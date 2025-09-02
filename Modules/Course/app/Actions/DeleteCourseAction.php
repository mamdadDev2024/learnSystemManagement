<?php

namespace Modules\Course\Actions;

use Exception;
use Modules\Course\Models\Course;

class DeleteCourseAction
{
    public function handle(Course $course)
    {
        if ($course->delete())
            throw new Exception('error on deleting course!');
    }
}
