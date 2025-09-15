<?php

namespace Modules\Lesson\Http\Requests;

use App\Contracts\ApiFormRequest;
use Illuminate\Validation\Rule;
use Modules\Lesson\Models\Lesson;

class ImportProgressRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "percentage" => "required|numeric|min:0|max:100",
            "started_at" => "nullalbe|date|before_or_equal:now",
            "completed_at" =>
                "nullable|date|after_or_equal:started_at|before_or_equal:now",
            "time_spent" => "nullable|integer|min:0",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            "percentage" => (float) $this->percentage,
            "time_spent" => $this->time_spent ? (int) $this->time_spent : null,
        ]);
    }
}
