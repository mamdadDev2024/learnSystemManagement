<?php

namespace Modules\User\Actions;

use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAction
{
    /**
     * Handle the login process.
     *
     * @param array $credentials ['email' => string, 'password' => string]
     * @return User
     * @throws ValidationException
     */
    public function handle(array $credentials): User
    {
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        return $user;
    }
}
