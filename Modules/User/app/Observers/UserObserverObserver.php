<?php

namespace Modules\User\Observers;

use Modules\User\Events\UserDeleted;
use Modules\User\Events\UserRegistered;
use Modules\User\Models\User;

class UserObserverObserver
{
    /**
     * Handle the UserObserver "created" event.
     */
    public function created(User $user): void {
        event(new UserRegistered($user));
    }
    public function deleted(User $user): void {
        event(new UserDeleted($user));
    }
}
