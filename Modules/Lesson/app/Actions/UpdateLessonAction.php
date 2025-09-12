<?php

namespace Modules\Lesson\Actions;

use Modules\Lesson\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Lesson\Events\AttachmentUploaded;
use Modules\Lesson\Events\VideoUploaded;

class UpdateLessonAction
{
    public function handle(Lesson $lesson, array $data)
    {
        if (isset($data['title']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $lesson->id);
        }

        if (isset($data['order']) && $data['order'] !== $lesson->order) {
            $this->reorderLessons($lesson, $data['order']);
        }

        $lesson->update($this->prepareData($data));

        if (isset($data['video']) || isset($data['attachment'])) {
            $this->handleMedia($lesson, $data);
        }

        return $lesson->fresh();
    }

    protected function generateUniqueSlug(string $title, int $lessonId): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Lesson::where('slug', $slug)->where('id', '!=', $lessonId)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    protected function reorderLessons(Lesson $lesson, int $newOrder): void
    {
        $course = $lesson->course;
        
        if ($newOrder > $lesson->order) {
            $course->lessons()
                ->where('order', '>', $lesson->order)
                ->where('order', '<=', $newOrder)
                ->decrement('order');
        } else {
            $course->lessons()
                ->where('order', '>=', $newOrder)
                ->where('order', '<', $lesson->order)
                ->increment('order');
        }
    }

    protected function prepareData(array $data): array
    {
        $defaults = [
            'is_published' => $data['is_published'] ?? false,
        ];

        return array_merge($defaults, $data);
    }

    protected function handleMedia(Lesson $lesson, array $data): void
    {
        if (isset($data['video']) && $data['video']->isValid()) {
            if ($lesson->video_url) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            
            $videoPath = $data['video']->store('videos', 'public');
            $lesson->video_url = $videoPath;
            VideoUploaded::dispatch($lesson);
        }
        
        if (isset($data['attachment']) && $data['attachment']->isValid()) {
            if ($lesson->attachment_url) {
                Storage::disk('public')->delete($lesson->attachment_url);
            }
            
            $attachmentPath = $data['attachment']->store('attachments', 'public');
            $lesson->attachment_url = $attachmentPath;
            AttachmentUploaded::dispatch($lesson);

        }

        $lesson->save();
    }
}