<?php

namespace Modules\User\App\Http\Requests;

use App\Contracts\ApiFormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Enums\VerificationActionType;

class VerifyUserRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return !auth('sanctum')->check();
    }

    public function rules(): array
    {
        return [
            'contact' => ['required', 'string'],
            'action'  => ['required', Rule::enum(VerificationActionType::class)],
        ];
    }
}
