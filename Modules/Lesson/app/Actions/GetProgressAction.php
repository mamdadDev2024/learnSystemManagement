<?php

namespace Modules\Lesson\Actions;

use Modules\Lesson\Models\Lesson;

class GetProgressAction
{
    public function handle(Lesson $lesson)
    {
        return $lesson
            ->progress()
            ->where("user_id", auth("sanctum")->id())
            ->first();
    }
}
