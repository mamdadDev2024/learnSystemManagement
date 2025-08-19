<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\VerificationCodeEmail;
use Modules\User\Enums\ContactType;
use Modules\User\Enums\VerificationActionType;
use Modules\User\Http\Requests\SendVerificationRequest;

class VerificationService
{
    public function getRetryTime(string $contact, ContactType $contactType, VerificationActionType $action): int
    {
        $cacheValue = Cache::get($this->getCacheKey($contact, $contactType, $action));
        if ($cacheValue !== null) {
            return now()->diffInSeconds($cacheValue['expiredAt']);
        }
        return 0;
    }

    public function generateCode(ContactType $contactType, string $contact, VerificationActionType $action, ?int $expiresInMinutes = null): int
    {
        $code = rand(100000, 999999);

        if ($expiresInMinutes === null) {
            $expiresInMinutes = $contactType === ContactType::EMAIL ? 2 : 1;
        }

        $expiresAt = now()->addMinutes($expiresInMinutes);

        Cache::put($this->getCacheKey($contact, $contactType, $action), [
            'code' => $code,
            'expiredAt' => $expiresAt
        ], $expiresAt);

        return $code;
    }

    public function forgetCode(SendVerificationRequest $request): void
    {
        Cache::forget(
            $this->getCacheKey(
                $request->input('contact'),
                $request->contactType,
                VerificationActionType::from($request->input('action'))
            )
        );
    }

    public function sendCodeAsSMS(SendVerificationRequest $request, int $code): int
    {
        try {
            // Http::post('' , [

            // ])->headers([

            // ]);
            return 1;
        } catch (\Throwable $e) {
            \Log::error($e->getMessage() . "on line" . $e->getLine());
            $this->forgetCode($request);
            return 0;
        }
    }

    public function sendCodeAsEmail(SendVerificationRequest $request, int $code): int
    {
        try {
            Mail::to($request->input('contact'))->send(new VerificationCodeEmail($code));
            return 1;
        } catch (\Throwable $e) {
            $this->forgetCode($request);
            return 0;
        }
    }

    public function getCacheKey(string $contact, ContactType $contactType, VerificationActionType $action): string
    {
        $key = hash('sha256', "{$action->value}:{$contactType->value}:{$contact}");
        return "verification:{$key}";
    }

    public function verifyCode(ContactType $contactType, string $contact, VerificationActionType $action, int $code): bool
{
    $cacheKey = $this->getCacheKey($contact, $contactType, $action);
    $cached = Cache::get($cacheKey);

    if (!$cached) {
        return false;
    }

    if ($cached['code'] != $code) {
        return false;
    }

    Cache::forget($cacheKey);

    return true;
}

}
