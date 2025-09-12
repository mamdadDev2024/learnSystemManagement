<?php

namespace Modules\Lesson\Actions;

class IndexLessonAction
{
    public function handle($course)
    {
        return $course
            ->lessons()
            ->with(["progress", "comments"])
            ->withCount(["likes", "comments", "views"])
            ->get()
            ->toArray();
    }
}
