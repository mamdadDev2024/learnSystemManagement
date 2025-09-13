<?php

namespace Modules\User\Actions;

use Modules\User\Models\User;

class DeleteUserAcion
{
    public function handle(User $user)
    {
        return $user->delete();
    }
}
