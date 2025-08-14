<?php

namespace Modules\User\Services;

use App\Services\BaseService;
use Illuminate\Validation\ValidationException;
use Modules\User\Actions\LoginAction;
use Modules\User\Actions\LogoutAction;
use Modules\User\Actions\RegisterAction;

class AuthService extends BaseService
{
    public function __construct(private RegisterAction $registerAction , private LoginAction $loginAction , private LogoutAction $logoutAction){}

    public function login(array $credentials)
    {
        return $this->execute(function () use ($credentials){
            $userData = $this->loginAction->handle($credentials);
            if ($userData)
                return $userData->createToken()->plainTextToken;
            throw ValidationException;
        });
    }

    public function register(array $data)
    {

    }

    public function logout()
    {

    }
}
