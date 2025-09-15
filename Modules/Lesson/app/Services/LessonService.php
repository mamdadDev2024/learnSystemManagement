<?php

namespace Modules\Lesson\Services;

use App\Contracts\BaseService;
use Illuminate\Support\Facades\Log;
use Modules\Course\Models\Course;
use Modules\Lesson\Actions\CreateLessonAction;
use Modules\Lesson\Actions\DeleteLessonAction;
use Modules\Lesson\Actions\GetProgressAction;
use Modules\Lesson\Actions\ImportProgressAction;
use Modules\Lesson\Actions\IndexLessonAction;
use Modules\Lesson\Actions\ShowLessonAction;
use Modules\Lesson\Actions\UpdateLessonAction;
use Modules\Lesson\Events\VideoUploaded;
use Modules\Lesson\Models\Lesson;

class LessonService extends BaseService
{
    public function __construct(
        private CreateLessonAction $createAction,
        private IndexLessonAction $indexAction,
        private ShowLessonAction $showAction,
        private UpdateLessonAction $updateAction,
        private DeleteLessonAction $deleteAction,
        private GetProgressAction $getProgressAction,
        private ImportProgressAction $importProgressAction,
    ) {}

    public function index(Course $course)
    {
        return $this->execute(fn() => $this->indexAction->handle($course));
    }

    public function show(Lesson $lesson)
    {
        return $this->execute(fn() => $this->showAction->handle($lesson));
    }

    public function update(Lesson $lesson, array $data)
    {
        return $this->execute(
            fn() => $this->updateAction->handle($lesson, $data),
        );
    }

    public function create(Course $course, array $data)
    {
        return $this->execute(
            function() use($course , $data) { 
                VideoUploaded::dispatch($this->createAction->handle($course, $data));
            }, successMessage: 'Lesson Created Successfully and Stood in Queue!'
        );
    }

    public function delete(Lesson $lesson)
    {
        return $this->execute(fn() => $this->deleteAction->handle($lesson));
    }

    public function getProgress(Lesson $lesson)
    {
        return $this->execute(
            fn() => $this->getProgressAction->handle($lesson),
        );
    }

    public function importProgress(Lesson $lesson, array $data)
    {
        return $this->execute(
            fn() => $this->importProgressAction->handle($lesson, $data),
        );
    }
}
