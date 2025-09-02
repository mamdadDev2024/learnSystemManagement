<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;

class IndexCourseAction
{
    public function handle() {
        return Course::with([
            'owner',
        ])->withCount([
            'likes',
            'comments',
            'views',
            'enrollments'
        ])->paginate(10);
    }
}
