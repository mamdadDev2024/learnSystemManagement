<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;

class CreateCourseAction
{
    public function handle(array $data)
    {
        $course = Course::create($data);
    }

}
