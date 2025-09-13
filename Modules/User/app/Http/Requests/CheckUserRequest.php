<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Enums\ContactType;

class CheckUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact' => [
                'bail',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    try {
                        $contactType = ContactType::detectContactType($value);
                    } catch (\InvalidArgumentException $e) {
                        $fail('Invalid Contact');
                        return;
                    }
                    switch ($contactType) {
                        case (ContactType::EMAIL):
                            $validator = Validator(['email' => $value], ['email']);
                            if ($validator->fails()) {
                                $fail('The contact must be a valid email address.');
                            }
                            return;

                        case (ContactType::PHONE):
                            $validator = Validator(['contact' => $value], ['phone:mobile']);
                            if ($validator->fails()) {
                                $fail('The contact must be a valid phone number.');
                            }
                            return;
                    }
                }
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
