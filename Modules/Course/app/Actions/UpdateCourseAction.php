<?php

namespace Modules\Course\Actions;

use Exception;
use Modules\Course\Models\Course;

class UpdateCourseAction
{
    public function handle(Course $course, array $data)
    {
        $course->fill($data);
        
        if ($course->isDirty()) {
            if (!$course->save()) {
                throw new Exception('Error updating course!');
            }
        }
        
        return $course;
    }

}
