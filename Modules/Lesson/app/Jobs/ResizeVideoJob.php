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

class ResizeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lesson;
    public $timeout = 3600;
    public $tries = 3;
    public $queue = 'video';

    public function __construct(Lesson $lesson)
    {
        Log::error('on resize job');
        $this->lesson = $lesson;
    }

    public function handle()
    {
        try {
            if (!Storage::exists($this->lesson->video_path)) {
                throw new \Exception("Video file not found");
            }

            $originalPath = $this->lesson->video_path;
            $filename = pathinfo($originalPath, PATHINFO_FILENAME);
            $resizedPath = "lessons/videos/resized/{$filename}.mp4";

            $format = new X264('aac');
            $format->setKiloBitrate(1000);

            FFmpeg::fromDisk('local')
                ->open($originalPath)
                ->export()
                ->toDisk('local')
                ->inFormat($format)
                ->resize(1280, 720)
                ->save($resizedPath);

            $this->lesson->update([
                'video_path' => $resizedPath,
                'video_resized' => true,
            ]);

            Storage::delete($originalPath);

            Log::info("Video resized successfully for lesson: {$this->lesson->id}");

        } catch (\Exception $e) {
            Log::error("ResizeVideoJob failed: " . $e->getMessage());
            $this->fail($e);
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->lesson->update([
            'processing_error' => 'Resize failed: ' . $exception->getMessage(),
            'video_processed' => false,
        ]);
    }
}