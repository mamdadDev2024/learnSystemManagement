<?php

namespace Modules\Lesson\Http\Requests;

use App\Contracts\ApiFormRequest;
use Illuminate\Validation\Rule;
use Modules\Lesson\Models\Lesson;

class UpdateLessonRequest extends ApiFormRequest
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
     */
    public function rules(): array
    {
        $lessonId = $this->route('lesson');

        return [
            'title' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
                Rule::unique('lessons')->ignore($lessonId)
            ],
            'slug' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
                Rule::unique('lessons')->ignore($lessonId)
            ],
            'description' => 'nullable|string|min:10',
            'order' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
            'course_id' => 'nullable|exists:courses,id',
            
            'attachment' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,txt|max:10240', // 10MB
            'video' => 'nullable|file|mimes:mp4,mkv,mov,avi|max:51200', // 50MB
            
            'attachment_url' => 'nullable|url|max:500',
            'attachment_name' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:500',
            'video_name' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.unique' => 'این عنوان درس قبلاً استفاده شده است.',
            'slug.unique' => 'این slug قبلاً استفاده شده است.',
            'attachment.max' => 'حجم فایل ضمیمه نباید بیشتر از 10 مگابایت باشد.',
            'video.max' => 'حجم ویدیو نباید بیشتر از 50 مگابایت باشد.',
            'course_id.exists' => 'دوره انتخاب شده معتبر نیست.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title)
            ]);
        }

        $this->merge(array_filter($this->all()));
    }
}