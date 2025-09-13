<?php

namespace Modules\User\Enums;

enum VerificationActionType: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';
    case FORGOT_PASSWORD = 'reset-password';
    case CHANGE_PASSWORD = 'change-password';
    case CHANGE_EMAIL = 'change-email';
    case CHANGE_PHONE = 'change-phone';
    case CHANGE_USERNAME = 'change-username';
    case CHANGE_NAME = 'change-name';

    public function isContactNeedToBeUnique(): bool
    {
        return in_array($this->value, [
            self::REGISTER->value
        ]);
    }

}
