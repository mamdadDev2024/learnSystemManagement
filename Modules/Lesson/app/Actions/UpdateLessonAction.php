<?php

namespace Modules\Lesson\Actions;

use Modules\Lesson\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Lesson\Events\AttachmentUploaded;
use Modules\Lesson\Events\VideoUploaded;

class UpdateLessonAction
{
    public function handle(Lesson $Lesson, array $data)
    {
        if (
            isset($data["title"]) &&
            (!isset($data["slug"]) || empty($data["slug"]))
        ) {
            $data["slug"] = $this->generateUniqueSlug(
                $data["title"],
                $Lesson->id,
            );
        }

        if (isset($data["order"]) && $data["order"] !== $Lesson->order) {
            $this->reorderLessons($Lesson, $data["order"]);
        }

        $Lesson->update($this->prepareData($data));

        if (isset($data["video"]) || isset($data["attachment"])) {
            $this->handleMedia($Lesson, $data);
        }

        return $Lesson->fresh();
    }

    protected function generateUniqueSlug(string $title, int $lessonId): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Lesson::where("slug", $slug)->where("id", "!=", $lessonId)->exists()
        ) {
            $slug = $originalSlug . "-" . $counter++;
        }

        return $slug;
    }

    protected function reorderLessons(Lesson $Lesson, int $newOrder): void
    {
        $course = $Lesson->course;

        if ($newOrder > $Lesson->order) {
            $course
                ->lessons()
                ->where("order", ">", $Lesson->order)
                ->where("order", "<=", $newOrder)
                ->decrement("order");
        } else {
            $course
                ->lessons()
                ->where("order", ">=", $newOrder)
                ->where("order", "<", $Lesson->order)
                ->increment("order");
        }
    }

    protected function prepareData(array $data): array
    {
        $defaults = [
            "is_published" => $data["is_published"] ?? false,
        ];

        return array_merge($defaults, $data);
    }

    protected function handleMedia(Lesson $Lesson, array $data): void
    {
        if (isset($data["video"]) && $data["video"]->isValid()) {
            if ($Lesson->video_url) {
                Storage::disk("public")->delete($Lesson->video_url);
            }

            $videoPath = $data["video"]->store("videos", "public");
            $Lesson->video_url = $videoPath;
            VideoUploaded::dispatch($Lesson);
        }

        if (isset($data["attachment"]) && $data["attachment"]->isValid()) {
            if ($Lesson->attachment_url) {
                Storage::disk("public")->delete($Lesson->attachment_url);
            }

            $attachmentPath = $data["attachment"]->store(
                "attachments",
                "public",
            );
            $Lesson->attachment_url = $attachmentPath;
            AttachmentUploaded::dispatch($Lesson);
        }

        $Lesson->save();
    }
}
