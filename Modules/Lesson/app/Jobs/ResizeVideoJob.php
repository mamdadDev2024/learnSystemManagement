<?php

namespace Modules\Lesson\Jobs;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Lesson\Models\Lesson;

class ResizeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lesson;
    public $tries = 3;
    public $timeout = 3600;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->onQueue("video"); // استفاده از متد به جای property
    }

    public function handle()
    {
        Log::info("Resizing video for lesson: " . $this->lesson->id);

        try {
            if (!Storage::exists($this->lesson->video_path)) {
                throw new \Exception("Video file not found");
            }

            $originalPath = $this->lesson->video_path;
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $resizedPath = "lessons/videos/resized/{$filename}.mp4";

            $format = new X264("aac");
            $format->setKiloBitrate(1000);

            FFmpeg::fromDisk("public")
                ->open($originalPath)
                ->export()
                ->toDisk("public")
                ->inFormat($format)
                ->resize(1280, 720)
                ->save($resizedPath);

            $this->lesson->update([
                "video_path" => $resizedPath,
                "video_resized" => true,
            ]);

            Storage::delete($originalPath);

            Log::info(
                "Video resized successfully for lesson: " . $this->lesson->id,
            );
        } catch (\Exception $e) {
            Log::error("Video resize failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("ResizeVideoJob failed: " . $exception->getMessage());
    }
}
