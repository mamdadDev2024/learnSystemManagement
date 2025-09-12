<?php

namespace Modules\Lesson\Actions;

use Modules\Course\Models\Course;
use Modules\Lesson\Events\AttachmentUploaded;
use Modules\Lesson\Events\VideoUploaded;
use Modules\Lesson\Models\Lesson;

class CreateLessonAction
{
    public function handle(Course $course ,array $data)
    {
            if (!isset($data['order'])) {
                $data['order'] = $this->getNextOrder($course);
            }

            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title']);
            }

            $lesson = $course->lessons()->create($this->prepareData($data));

            $this->handleMedia($lesson, $data);

            return $lesson->fresh();
    }

    protected function getNextOrder(Course $course): int
    {
        $lastOrder = $course->lessons()->max('order');
        return ($lastOrder ?? 0) + 1;
    }

    protected function generateUniqueSlug(string $title): string
    {
        $slug = \Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Lesson::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    protected function prepareData(array $data): array
    {
        return array_merge([
            'is_published' => false,
            'duration' => 0,
        ], $data);
    }

    protected function handleMedia(Lesson $lesson, array $data): void
    {

        if (isset($data['video']) && $data['video']->isValid()) {
            $videoPath = $data['video']->store('videos', 'public');
            $lesson->video_url = $videoPath;
            $lesson->save();
            VideoUploaded::dispatch($lesson);

        }
        
        if (isset($data['attachment']) && $data['attachment']->isValid()) {
            $attachmentPath = $data['attachment']->store('attachments', 'public');
            $lesson->attachment_url = $attachmentPath;
            $lesson->save();
            AttachmentUploaded::dispatch($lesson);

        }
    }

    public function createWithDefaults(Course $course, string $title, ?string $description = null)
    {
        return $this->handle($course, [
            'title' => $title,
            'description' => $description,
            'is_published' => false,
            'duration' => 0,
        ]);
    }
}
