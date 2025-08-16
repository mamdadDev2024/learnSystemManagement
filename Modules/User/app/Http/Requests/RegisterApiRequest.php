<?php

namespace Modules\User\Http\Requests;

use App\Contracts\ApiFormRequest;
use ApiResponse;

class RegisterApiRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required|max:100|min:2',
            'email' => 'email|required|unique:users',
            'password' => 'string|required|min:6'
        ];
    }
}
