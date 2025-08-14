<?php

namespace Modules\User\Actions;

use Illuminate\Support\Facades\Hash;
use Modules\User\App\Models\User;

class RegisterAction
{
    public function handle(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data)->createToken()->plainTextToken;
    }
}
