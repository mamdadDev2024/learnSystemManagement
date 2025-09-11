<?php

namespace Modules\Course\Actions;

use Exception;
use Modules\Course\Models\Course;

class DeleteCourseAction
{
    public function handle(Course $course)
    {
            if ($course->enrollments()->where('status', 'active')->exists()) {
                throw new Exception('Cannot delete course with active enrollments!');
            }
            
            if (!$course->delete()) {
                throw new Exception('Failed to delete course!');
            }
                        
            return true;
    }
}
