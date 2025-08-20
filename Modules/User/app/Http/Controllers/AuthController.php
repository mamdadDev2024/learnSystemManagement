<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\User\Services\AuthService;
use App\Contracts\ApiResponse;
use Modules\User\Http\Requests\LoginApiRequest;
use Modules\User\Http\Requests\RegisterApiRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Requests\SendVerificationRequest;
use Modules\User\Events\UserRegistered;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function login(LoginApiRequest $request)
    {
        $result = $this->service->login($request->validated());

        return $result->status
            ? ApiResponse::success($result->data, 'Login successful', 200)
            : ApiResponse::error($result->message ?? 'Login failed', $result->data, 401);
    }

    public function register(RegisterApiRequest $request)
    {
        $result = $this->service->register($request->validated());

        if ($result->status) {
            event(new UserRegistered($result->data));
            return ApiResponse::success($result->data, 'User registered successfully', 201);
        }

        return ApiResponse::error($result->message ?? 'Registration failed', $result->data, 422);
    }

    public function logout()
    {
        $result = $this->service->logout();

        return $result->status
            ? ApiResponse::success(null, 'Logged out successfully', 200)
            : ApiResponse::error($result->message ?? 'Logout failed', null, 400);
    }

    public function me()
    {
        $user = auth('sanctum')->user();

        return $user
            ? ApiResponse::success($user, 'User profile fetched successfully', 200)
            : ApiResponse::error('User not authenticated', null, 401);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->service->resetPassword($request->validated());

        return $result->status
            ? ApiResponse::success(null, 'Reset Password successfully', 200)
            : ApiResponse::error('Reset Password failed!', null, 422);
    }
}
