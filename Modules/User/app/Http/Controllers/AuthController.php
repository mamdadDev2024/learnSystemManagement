<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\User\Services\AuthService;
use App\Contracts\ApiResponse;
use Modules\User\Http\Requests\LoginApiRequest;
use Modules\User\Http\Requests\RegisterApiRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function login(LoginApiRequest $request)
    {
        $result = $this->service->login($request->validated());

        if ($result->status) {
            return ApiResponse::success($result->data, 'Login successful');
        }

        return ApiResponse::error($result->message ?? 'Login failed', $result->data, 401);
    }

    public function register(RegisterApiRequest $request)
    {
        $result = $this->service->register($request->validated());

        if ($result->status) {
            return ApiResponse::success($result->data, 'User registered successfully', 201);
        }

        return ApiResponse::error($result->message ?? 'Registration failed', $result->data);
    }

    public function logout()
    {
        $result = $this->service->logout();

        if ($result->status) {
            return ApiResponse::success(null, 'Logged out successfully');
        }

        return ApiResponse::error($result->message ?? 'Logout failed');
    }

    public function me()
    {
        $user = auth()->user();

        if ($user) {
            return ApiResponse::success($user, 'User profile fetched successfully');
        }

        return ApiResponse::error('User not authenticated', null, 401);
    }
}
