<?php

namespace Modules\Lesson\Services;

use App\Contracts\BaseService;
use Modules\Course\Models\Course;
use Modules\Lesson\Actions\CreateLessonAction;
use Modules\Lesson\Actions\DeleteLessonAction;
use Modules\Lesson\Actions\IndexLessonAction;
use Modules\Lesson\Actions\ShowLessonAction;
use Modules\Lesson\Actions\UpdateLessonAction;
use Modules\Lesson\Models\Lesson;

class LessonService extends BaseService
{
    public function __construct(private CreateLessonAction $createAction , private IndexLessonAction $indexAction , private ShowLessonAction $showAction , private UpdateLessonAction $updateAction , private DeleteLessonAction $deleteAction) {}

    public function index(Course $course)
    {
        return $this->execute(fn () => $this->indexAction->handle($course));
    }

    public function show(Lesson $lesson)
    {
        return $this->execute(fn () => $this->showAction->handle($lesson));
    }

    public function update(Lesson $lesson)
    {
        return $this->execute(fn () => $this->updateAction->handle($lesson));
    }

    public function create(Course $course , array $data)
    {
        return $this->execute(fn () => $this->createAction->handle($course , $data));
    }

    public function delete(Lesson $lesson)
    {
        return $this->execute(fn () => $this->deleteAction->handle($lesson));
    }
}
