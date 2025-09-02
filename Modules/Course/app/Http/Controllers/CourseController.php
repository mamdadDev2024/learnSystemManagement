<?php

namespace Modules\Course\Http\Controllers;

use App\Contracts\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\App\Http\Requests\StoreCourseRequest;
use Modules\Course\App\Http\Requests\UpdateCourseRequest;
use Modules\Course\Models\Course;
use Modules\Course\Services\CourseService;
use Modules\Interaction\Services\InteractService;

class CourseController extends Controller
{
    public function __construct(private CourseService $service , private InteractService $interactService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->service->index();

        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::success($result->message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $result = $this->service->create($request->validated());
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message);
    }

    /**
     * Show the specified resource.
     */
    public function show(Course $course)
    {
        $result = $this->service->get($course);

        $this->interactService->recordView($course);
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course) {

        $result = $this->service->update($course , $request->validated());
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course) {

        $result = $this->service->delete($course);
        return $result->status
            ? ApiResponse::success($result->data , $result->message)
            : ApiResponse::error($result->message);
    }
}
