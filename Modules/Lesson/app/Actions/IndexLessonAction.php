<?php

namespace Modules\Lesson\Actions;

use Modules\Course\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class IndexLessonAction
{
    public function handle($course)
    {
        dd( $course->lessons()
            ->with(['progress', 'comments'])
            ->withCount(['likes', 'comments', 'views'])->get()->toArray());
    }
}