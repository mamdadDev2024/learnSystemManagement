<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;

class CreateCourseAction
{
    public function handle(array $data): Course
    {
        $data['slug'] = \Str::slug($data['title']);
        return auth("sanctum")->user()->courses()->create($data);
    }
}
