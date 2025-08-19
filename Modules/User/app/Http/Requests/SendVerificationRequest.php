<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\ContactType;
use Modules\User\Enums\VerificationActionType;
use Modules\User\Services\VerificationService;

class SendVerificationRequest extends FormRequest
{
    public ?ContactType $contactType = null;
    public ?VerificationActionType $actionType = null;

    public function prepareForValidation()
    {
        $this->contactType = ContactType::detectContactType($this->input('contact') ?? '');
        $this->actionType  = VerificationActionType::tryFrom($this->input('action') ?? '');
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $contact = $this->input('contact');
            $retryTime = (new VerificationService)->getRetryTime($contact, $this->contactType, $this->actionType);

            if ($retryTime > 0) {
                $validator->errors()->add('contact', __('auth::validation.contact_retry_time', ['retry_time' => $retryTime]));
            }
        });
    }

    public function rules(): array
    {
        $contactRules = [];

        try {
            $contactRules = $this->input('contact') ? $this->getContactValidationRule() : ['prohibited'];
        } catch (\InvalidArgumentException $e) {
            $contactRules = ['prohibited'];
        }

        return [
            'action' => [
                'bail',
                'required',
                'string',
                new Enum(VerificationActionType::class)
            ],
            'contact' => array_merge([
                'bail',
                'required',
                'string',
            ], $contactRules),
        ];
    }

    public function getContactValidationRule(): array
    {
        if ($this->contactType === ContactType::EMAIL) {
            return [
                'email:rfc,dns',
                Rule::when(
                    $this->actionType->isContactNeedToBeUnique(),
                    ['unique:users,email'],
                    ['exists:users,email']
                )
            ];
        }

        if ($this->contactType === ContactType::PHONE) {
            return [
                'phone:mobile',
                Rule::when(
                    $this->actionType->isContactNeedToBeUnique(),
                    ['unique:users,phone'],
                    ['exists:users,phone']
                )
            ];
        }

        throw new \InvalidArgumentException('Invalid contact');
    }

    public function authorize(): bool
    {
        return true;
    }
}
