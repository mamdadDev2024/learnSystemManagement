<?php

namespace Modules\User\Actions;

use Modules\User\Models\User;

class UpdateUserAcion
{
    public function handle(User $user , array $data) {
        if ($user->isDirty())
            $user->update($data);
            return $user->refresh();
    }
}
