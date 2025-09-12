<?php

namespace Modules\Lesson\Actions;

use Modules\Lesson\Models\Lesson;

class GetProgressAction
{
    public function handle(Lesson $lesson)
    {
        // Calculate progress based on lesson completion status
        return $lesson->progress();
    }
}
