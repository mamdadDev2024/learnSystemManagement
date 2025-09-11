<?php

namespace Modules\Enrollment\Services;

use App\Contracts\BaseService;
use Modules\Enrollment\Actions\CheckEnrollmentAction;
use Modules\Enrollment\Actions\CreateEnrollmentAction;
use Modules\Enrollment\Actions\DeleteEnrollmentAction;
use Modules\Enrollment\Actions\UpdateEnrollmentAction;
use Modules\Enrollment\Models\Enrollment;

class EnrollmentService extends BaseService
{
    public function __construct(
        private CreateEnrollmentAction $createAction,
        private UpdateEnrollmentAction $updateAction,
        private CheckEnrollmentAction $checkAction,
        private DeleteEnrollmentAction $deleteAction,
    ) {}

    public function create(array $data): Enrollment
    {
        return $this->excute(function () use($data) {
            return $this->createAction->handle($data);
        })
    }

    public function update(Enrollment $enrollment, array $data): Enrollment
    {
        return $this->excute(function () use($enrollment, $data) {
            return $this->updateAction->handle($enrollment, $data);
        })
    }

    public function check(array $data): bool
    {
        return $this->excute(function () use($data) {
            return $this->checkAction->handle($data);
        })
    }

    public function delete(Enrollment $enrollment): void
    {
        return $this->excute(function () use($enrollment) {
            $this->deleteAction->handle($enrollment);
        })
    }
}
