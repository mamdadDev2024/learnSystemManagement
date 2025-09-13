<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\VerificationCodeEmail;
use Modules\User\Enums\ContactType;
use Modules\User\Enums\VerificationActionType;
use App\Contracts\ServiceResponse;
use Modules\User\Models\User;

class VerificationService
{
    public function getRetryTime(string $contact, ContactType $contactType, VerificationActionType $action): int
    {
        $cacheValue = Cache::get($this->getCacheKey($contact, $contactType, $action));
        return $cacheValue ? now()->diffInSeconds($cacheValue['expiredAt']) : 0;
    }

    public function generateCode(ContactType $contactType, string $contact, VerificationActionType $action, ?int $expiresInMinutes = null): int
    {
        $code = rand(100000, 999999);
        $expiresInMinutes ??= $contactType === ContactType::EMAIL ? 2 : 1;
        $expiresAt = now()->addMinutes($expiresInMinutes);

        Cache::put($this->getCacheKey($contact, $contactType, $action), [
            'code' => $code,
            'expiredAt' => $expiresAt
        ], $expiresAt);

        return $code;
    }

    public function sendVerificationCode(ContactType $contactType, string $contact, VerificationActionType $action): ServiceResponse
    {
        try {
            $code = $this->generateCode($contactType, $contact, $action);

            $sent = match ($contactType) {
                ContactType::EMAIL => $this->sendCodeAsEmail($contact, $code),
                ContactType::PHONE => $this->sendCodeAsSMS($contact, $code),
            };

            return $sent
                ? ServiceResponse::success(message: 'Verification code sent successfully')
                : ServiceResponse::error('Verification code sending failed');

        } catch (\Throwable $e) {
            \Log::error("VerificationService sendVerificationCode error: {$e->getMessage()}");
            return ServiceResponse::error('Verification code sending failed');
        }
    }

    public function verifyCode(ContactType $contactType, string $contact, VerificationActionType $action, int $code): ServiceResponse
    {
        $cacheKey = $this->getCacheKey($contact, $contactType, $action);
        $cached = Cache::get($cacheKey);

        if (!$cached) {
            return ServiceResponse::error('Verification code is expired or does not exist');
        }

        if ($cached['code'] != $code) {
            return ServiceResponse::error('Verification code is invalid');
        }

        Cache::forget($cacheKey);
        return ServiceResponse::success(message: 'Verification code verified successfully');
    }

    protected function sendCodeAsSMS(string $contact, int $code): bool
    {
        try {
            $user = User::wherePhone($contact)->first();
            return true;
        } catch (\Throwable $e) {
            \Log::error("SMS sending failed: {$e->getMessage()}");
            return false;
        }
    }

    protected function sendCodeAsEmail(string $contact, int $code): bool
    {
        try {
            $user = User::whereEmail($contact)->first();
            Mail::to($contact)->send(new VerificationCodeEmail($code, $user));
            return true;
        } catch (\Throwable $e) {
            \Log::error("Email sending failed: {$e->getMessage()}");
            return false;
        }
    }

    protected function getCacheKey(string $contact, ContactType $contactType, VerificationActionType $action): string
    {
        return 'verification:' . hash('sha256', "{$action->value}:{$contactType->value}:{$contact}");
    }
}
