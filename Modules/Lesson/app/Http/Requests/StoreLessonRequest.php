<?php

namespace Modules\Lesson\Http\Requests;

use App\Contracts\ApiFormRequest;

class StoreLessonRequest extends ApiFormRequest
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
            'title' => 'required|string|min:3|max:255|unique:lessons',
            'description' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:.zip,.rar',
            'video' => 'nullable|file|mimes:.mp4,.mkv',
        ];
    }
}
