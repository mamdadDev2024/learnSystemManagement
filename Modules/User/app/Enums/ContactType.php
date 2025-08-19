<?php

namespace Modules\User\Enums;

use Illuminate\Validation\ValidationException;

enum ContactType: string {
    case EMAIL = 'email';
    case PHONE = 'phone';

    public static function detectContactType(string $contact): self {
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return self::EMAIL;
        }
        if (preg_match('/^(?:\+98|0)?9\d{9}$/', $contact)) {
            return self::PHONE;
        }

        throw ValidationException::withMessages([
            'contact' => __('Invalid contact type'),
        ]);
    }
}
