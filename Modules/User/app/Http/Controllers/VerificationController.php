<?php

namespace Modules\User\Http\Controllers;

use App\Contracts\ApiResponse;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\SendVerificationRequest;
use Modules\User\Enums\ContactType;
use Modules\User\Services\VerificationService;
use App\Contracts\ServiceResponse;
use Modules\User\Http\Requests\ReceiveCodeVerificationRequest;

class VerificationController extends Controller
{
    public function __construct(private VerificationService $service)
    {
    }

    public function sendVerificationCode(SendVerificationRequest $request)
    {
        $response = $this->service->sendVerificationCode(
            $request->contactType,
            $request->validated()['contact'],
            $request->actionType
        );

        return $response->status
            ? ApiResponse::success(message: $response->message)
            : ApiResponse::error($response->message);
    }


    public function verifyCode(ReceiveCodeVerificationRequest $request)
    {
        $validated = $request->validated();

        $response = $this->service->verifyCode(
            $request->contactType,
            $validated['contact'],
            $request->actionType,
            $validated['code']
        );

        return $response->status
            ? ApiResponse::success(message: $response->message)
            : ApiResponse::error($response->message, statusCode: 422);
    }

}
