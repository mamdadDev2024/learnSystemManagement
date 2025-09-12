<?php

namespace Modules\Lesson\Actions;

use Modules\Lesson\Models\Lesson;

class ShowLessonAction
{
    public function handle(Lesson $lesson)
    {
        return $lesson->with(['user' , 'course' , 'comments' , 'comments.user'])->withCount(['comments' , 'views' , 'likes']);
    }
}
