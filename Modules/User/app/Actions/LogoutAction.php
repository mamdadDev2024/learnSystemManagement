<?php

namespace Modules\User\Actions;

use Modules\User\Models\User;

class LogoutAction
{
    public function handle(User $user, bool $allDevices = false): void
    {
        if ($allDevices) {
            $user->tokens()->delete();
        } else {
            $user->currentAccessToken()?->delete();
        }
    }
}
