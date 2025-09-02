<?php

namespace Modules\Course\Services;

use App\Contracts\ApiResponse;
use App\Contracts\BaseService;
use Modules\Course\Actions\IndexCourseAction;
use Modules\Course\Actions\CreateCourseAction;
use Modules\Course\Actions\UpdateCourseAction;
use Modules\Course\Actions\DeleteCourseAction;
use Modules\Course\Actions\ShowCourseAction;
use Modules\Course\Models\Course;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CourseService extends BaseService
{
    public function __construct(
        private IndexCourseAction $indexAction,
        private CreateCourseAction $createAction,
        private UpdateCourseAction $updateAction,
        private DeleteCourseAction $deleteAction,
        private ShowCourseAction $showAction
    ) {}

    public function index()
    {
        return $this->execute(function () {
            $courses = $this->indexAction->handle();

            if ($courses->count() <= 0) {
                throw new Exception('No courses found', 404);
            }

            return $courses;
        });
    }

    public function get(Course $course)
    {
        return $this->execute(function () use ($course) {
            return $this->showAction->handle($course);
        });
    }

    public function delete(Course $course)
    {
        return $this->execute(function () use ($course) {
            $result = $this->deleteAction->handle($course);

            if (!$result) {
                throw new Exception('Failed to delete course', 500);
            }

            return $result;
        });
    }

    public function update(Course $course, array $data)
    {
        return $this->execute(function () use ($course, $data) {
            $updatedCourse = $this->updateAction->handle($course, $data);

            if (!$updatedCourse) {
                throw new Exception('Failed to update course', 500);
            }

            return $updatedCourse;
        });
    }

    public function create(array $data)
    {
        return $this->execute(function () use ($data) {
            $course = $this->createAction->handle($data);

            if (!$course) {
                throw new Exception('Failed to create course', 500);
            }

            return $course;
        });
    }

    public function findOrFail($id)
    {
        return $this->execute(function () use ($id) {
            $course = Course::find($id);

            if (!$course) {
                throw new ModelNotFoundException("Course with ID {$id} not found", 404);
            }

            return $course;
        });
    }

    public function publish(Course $course): Course
    {
        $course->update(['published' => true]);
        return $course;
    }

    public function unpublish(Course $course): Course
    {
        $course->update(['published' => false]);
        return $course;
    }
}
