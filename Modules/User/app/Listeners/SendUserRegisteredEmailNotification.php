<?php

namespace Modules\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Events\UserRegistered;
use Modules\User\Notifications\UserRegistered as UserRegisteredNotification;

class SendUserRegisteredEmailNotification implements ShouldQueue
{
    public function __construct() {}

    public function handle(UserRegistered $event): void
    {
        $event->user->notify(new UserRegisteredNotification());
    }
}
