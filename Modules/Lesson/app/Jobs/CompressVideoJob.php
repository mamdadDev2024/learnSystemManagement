<?php

namespace Modules\Lesson\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Lesson\Models\Lesson;
use ProtoneMedia\LaravelFFmpeg\Support\FFmpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompressVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lesson;
    public $timeout = 3600;
    public $tries = 3;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->onQueue("video");
    }

    public function handle()
    {
        Log::info("Starting video compression for lesson: {$this->lesson->id}");

        try {
            // Check if video_url is null or empty
            if (empty($this->lesson->video_url)) {
                throw new \Exception("Video URL is empty or null");
            }

            $videoPath = $this->lesson->video_url;

            // Additional validation to ensure it's a string
            if (!is_string($videoPath)) {
                throw new \Exception("Video URL is not a valid string");
            }

            // Check if the file actually exists
            if (!Storage::disk("public")->exists($videoPath)) {
                throw new \Exception(
                    "Resized video not found at path: " .
                        ($videoPath ?? "NULL"),
                );
            }

            $filename = pathinfo($videoPath, PATHINFO_FILENAME);
            $compressedPath = "lessons/videos/compressed/{$filename}.mp4";

            $format = new X264("aac");
            $format->setKiloBitrate(800);
            $format->setAdditionalParameters(["-crf", "28"]);

            FFmpeg::fromDisk("public")
                ->open($videoPath)
                ->export()
                ->toDisk("public")
                ->inFormat($format)
                ->save($compressedPath);

            // Verify the compressed file was created
            if (!Storage::disk("public")->exists($compressedPath)) {
                throw new \Exception(
                    "Compressed video was not created successfully",
                );
            }

            $this->lesson->update([
                "video_url" => $compressedPath,
                "video_compressed" => true,
                "video_size" => Storage::disk("public")->size($compressedPath),
            ]);

            // Only delete the original if compression succeeded
            Storage::disk("public")->delete($videoPath);

            Log::info(
                "Video compressed successfully for lesson: {$this->lesson->id}",
            );
        } catch (\Exception $e) {
            Log::error(
                "CompressVideoJob failed for lesson {$this->lesson->id}: " .
                    $e->getMessage(),
            );
            $this->fail($e);
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->lesson->update([
            "processing_error" =>
                "Compression failed: " . $exception->getMessage(),
            "video_processed" => false,
        ]);
    }
}
