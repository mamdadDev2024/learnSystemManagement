<?php

namespace Modules\User\Http\Requests;

use App\Contracts\ApiFormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Enums\ContactType;
use Modules\User\Enums\VerificationActionType;
use Modules\User\Services\VerificationService;

class ReceiveCodeVerificationRequest extends ApiFormRequest
{
    public $contactType;
    public $actionType;

    public function authorize(): bool
    {
        return !auth('sanctum')->check();
    }

    public function prepareForValidation(): void
    {
        $contact = $this->input('contact') ?? '';
        $action  = $this->input('action') ?? '';

        $this->contactType = ContactType::detectContactType($contact);

        $this->actionType = VerificationActionType::tryFrom($action);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $contact = $this->input('contact');
            $retryTime = (new VerificationService)->getRetryTime(
                $contact,
                $this->contactType,
                $this->actionType
            );
        });
    }

    public function rules(): array
    {
        return [
            'contact' => ['required', 'string'],
            'code'    => ['required', 'digits:6'],
            'action'  => ['required', Rule::enum(VerificationActionType::class)],
        ];
    }
}
