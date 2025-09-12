<?php

namespace Modules\Lesson\Http\Controllers;

use App\Contracts\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Models\Course;
use Modules\Lesson\Http\Requests\StoreLessonRequest;
use Modules\Lesson\Http\Requests\UpdateLessonRequest;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Services\LessonService;

class LessonController extends Controller
{
    public function __construct(private LessonService $service){}
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $result = $this->service->index($course);
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message , $result->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request, Course $course)
    {
        $result = $this->service->create($course , $request->validated());
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message , $result->data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Lesson $lesson)
    {
        $result = $this->service->show($lesson);
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message , $result->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $result = $this->service->update($lesson , $request->validated());
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message , $result->data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $result = $this->service->delete($lesson);
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message , $result->data);
    }
}
