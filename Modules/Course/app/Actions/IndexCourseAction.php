<?php

namespace Modules\Course\Actions;

use Modules\Course\Models\Course;
use Illuminate\Http\Request;

class IndexCourseAction
{
    public function handle()
    {
        // $sortBy = $request->get("sort_by", "created_at");
        // $sortOrder = $request->get("sort_order", "desc");
        return Course::with(["owner"])
            ->withCount(["likes", "comments", "views", "enrollments"])
            // ->orderBy($sortBy, $sortOrder)
            ->paginate(10);
    }
}
