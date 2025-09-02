<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;
use Modules\Course\Transformers\CourseResource;

class ShowCourseAction
{
    public function handle(Course $course){
        return $course->with(['owner' , 'comments'])->withCount(['views' , 'likes' , 'comments' , 'users'])->get()->toResource(CourseResource::class);
    }

}
