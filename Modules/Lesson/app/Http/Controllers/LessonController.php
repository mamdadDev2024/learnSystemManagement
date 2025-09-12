<?php

namespace Modules\Lesson\Http\Controllers;

use App\Contracts\ApiResponse;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Course\Models\Course;
use Modules\Lesson\Http\Requests\StoreLessonRequest;
use Modules\Lesson\Http\Requests\ImportProgressRequest;
use Modules\Lesson\Http\Requests\UpdateLessonRequest;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Services\LessonService;

class LessonController extends Controller
{
    public function __construct(private LessonService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Course $Course)
    {
        if (!$Course->exists) {
            abort(404, "course not found!");
        }
        $result = $this->service->index($Course);
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request, Course $Course)
    {
        if (!$Course->exists) {
            abort(404, "course not found!");
        }
        $result = $this->service->create($Course, $request->validated());
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Lesson $Lesson)
    {
        if (!$Lesson->exists) {
            abort(404, "lesson not found!");
        }
        $result = $this->service->show($Lesson);
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $Lesson)
    {
        if (!$Lesson->exists()) {
            abort(404, "lesson not found!");
        }
        $result = $this->service->update($Lesson, $request->validated());
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $Lesson)
    {
        if (!$Lesson->exists) {
            abort(404, "lesson not found!");
        }
        $result = $this->service->delete($Lesson);
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    public function getProgress(Lesson $Lesson)
    {
        $result = $this->service->getProgress($Lesson);
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }

    public function importProgress(
        ImportProgressRequest $request,
        Lesson $Lesson,
    ) {
        $result = $this->service->importProgress(
            $Lesson,
            $request->validated(),
        );
        return $result->status
            ? ApiResponse::success($result->data, $result->message)
            : ApiResponse::error($result->message, $result->data);
    }
}
