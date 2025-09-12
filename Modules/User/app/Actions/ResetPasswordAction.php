<?php

namespace Modules\User\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Models\User;

class ResetPasswordAction
{
    /**
     * Handle the resetPassword process.
     *
     * @param array $data ['password' => string, 'newPassword' => string, 'newPasswordConfirmation' => string]
     * @return User
     * @throws ValidationException
     */
    public function handle(array $data): User
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($data['newPassword']);
        $user->save();

        return $user;
    }
}
