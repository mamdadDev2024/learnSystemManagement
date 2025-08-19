<?php

namespace Modules\User\App\Http\Requests;

use App\Contracts\ApiFormRequest;

class UserUpdateRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:100',
            'email' => 'required|email|unique:users,email,'. auth('sanctum')->id()
        ];
    }
}
