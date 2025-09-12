<?php

namespace Modules\User\Services;

use App\Contracts\BaseService;
use App\Contracts\ServiceResponse;
use Illuminate\Validation\ValidationException;
use Modules\User\Actions\LoginAction;
use Modules\User\Actions\LogoutAction;
use Modules\User\Actions\RegisterAction;
use Modules\User\Actions\ResetPasswordAction;

class AuthService extends BaseService
{
    public function __construct(
        private RegisterAction $registerAction,
        private LoginAction $loginAction,
        private LogoutAction $logoutAction,
        private ResetPasswordAction $resetPasswordAction
    ) {}

    public function login(array $credentials): ServiceResponse
    {
        return $this->execute(function () use ($credentials) {
            $user = $this->loginAction->handle($credentials);

            if ($user) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return [
                    'user'  => $user,
                    'token' => $token
                ];
            }

            throw ValidationException::withMessages([
                'email' => 'Invalid credentials provided.'
            ]);
        }, 'Login service error');
    }

    public function register(array $data): ServiceResponse
    {
        return $this->execute(function () use ($data) {
            $user = $this->registerAction->handle($data);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token
            ];
        }, 'Registration service error');
    }

    public function logout(): ServiceResponse
    {
        return $this->execute(function () {
            $this->logoutAction->handle(auth()->user());

            return [
                'message' => 'Logged out successfully.'
            ];
        }, 'Logout service error', false);
    }

    public function resetPassword(array $data): ServiceResponse
    {
        return $this->execute(function () use ($data){
            $this->resetPasswordAction->handle($data);
            return [
                'message' => 'password reset successful'
            ];
        } , 'reset password failed!');
    }
}
