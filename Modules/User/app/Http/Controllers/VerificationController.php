<?php

namespace Modules\User\Http\Controllers;

use App\Contracts\ApiResponse;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\ReceiveCodeVerificationRequest;
use Modules\User\Enums\ContactType;
use Modules\User\Http\Requests\SendVerificationRequest;
use Modules\User\Services\VerificationService;

class VerificationController extends Controller
{
    public function __construct(private VerificationService $service){}

    public function sendVerificationCode(SendVerificationRequest $request)
    {
        $validated = $request->validated();

        $code = $this->service->generateCode(
            $validated['contactType'],
            $validated['contact'],
            $validated['action']
        );

        $responseStatus = false;

        if ($validated['contactType'] == ContactType::EMAIL) {
            $responseStatus = $this->service->sendCodeAsEmail($validated, $code);
        } elseif($validated['contactType'] == ContactType::PHONE) {
            $responseStatus = $this->service->sendCodeAsSMS($validated, $code);
        }

        return $responseStatus
            ? ApiResponse::success(message: 'Verification code sent successfully')
            : ApiResponse::error('Verification code sending failed');
    }

    public function verifyCode(ReceiveCodeVerificationRequest $request)
    {
        $validated = $request->validated();

        $contact = $validated['contact'];
        $actionType = $validated['action'];
        $code = $validated['code'];
        $contactType = $request->contactType;

        $isValid = $this->service->verifyCode($contactType, $contact, $actionType, $code);

        if ($isValid) {
            return ApiResponse::success(message: 'Verification successful');
        }

        return ApiResponse::error('Invalid or expired verification code');
    }

}
