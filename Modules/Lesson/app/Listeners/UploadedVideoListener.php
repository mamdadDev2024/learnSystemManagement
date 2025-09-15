<?php

namespace Modules\Lesson\Listeners;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Modules\Lesson\Events\VideoUploaded;
use Modules\Lesson\Jobs\CompressVideoJob;
use Modules\Lesson\Jobs\ResizeVideoJob;

class UploadedVideoListener implements ShouldQueue, ShouldBeEncrypted
{
    use InteractsWithQueue;

    public $timeout = 3600;
    public $tries = 3;
    public $queue = "video";

    public function handle(VideoUploaded $event)
    {
        Log::info("Video processing started");

        Bus::chain([
            new ResizeVideoJob($event->lesson),
            new CompressVideoJob($event->lesson),
        ])
            ->catch(function (\Throwable $e) use ($event) {
                $event->lesson->update([
                    "processing_error" =>
                        "Video processing chain failed: " . $e->getMessage(),
                    "video_processed" => false,
                ]);
                Log::error(
                    "Video processing chain failed: " . $e->getMessage(),
                );
            })
            ->onQueue("video")
            ->dispatch();
    }

    public function failed(VideoUploaded $event, \Throwable $exception)
    {
        $event->lesson->update([
            "processing_error" =>
                "Video upload processing failed: " . $exception->getMessage(),
            "video_processed" => false,
        ]);
    }
}
