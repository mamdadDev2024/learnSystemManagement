<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\ContactType;
use Modules\User\Enums\VerificationActionType;

class ReceiveCodeVerificationRequest extends FormRequest
{
    public ?ContactType $contactType = null;
    public ?VerificationActionType $actionType = null;

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->contactType = ContactType::detectContactType($this->input('contact') ?? '');
        $this->actionType = VerificationActionType::tryFrom($this->input('action') ?? '');
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) return;

            $code = $this->input('code');
            if (!is_numeric($code) || strlen((string)$code) !== 6) {
                $validator->errors()->add('code', 'The verification code must be a 6-digit number.');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $contactRules = [];

        try {
            $contactRules = $this->input('contact') ? $this->getContactValidationRule() : ['prohibited'];
        } catch (\InvalidArgumentException $e) {
            $contactRules = ['prohibited'];
        }

        return [
            'contact' => array_merge(['required', 'string'], $contactRules),
            'action'  => ['required', 'string', new Enum(VerificationActionType::class)],
            'code'    => ['required', 'digits:6', 'numeric'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get contact-specific validation rules.
     */
    protected function getContactValidationRule(): array
    {
        if ($this->contactType === ContactType::EMAIL) {
            return ['email:rfc,dns', 'exists:users,email'];
        }

        if ($this->contactType === ContactType::PHONE) {
            return ['phone:mobile', 'exists:users,phone'];
        }

        throw new \InvalidArgumentException('Invalid contact type.');
    }
}
