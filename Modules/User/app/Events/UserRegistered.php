<?php

namespace Modules\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Models\User;

class UserRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}
}
