<?php

namespace Modules\Lesson\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Lesson\Events\VideoUploaded;
use Modules\Lesson\Jobs\ProcessVideoJob;

class UploadedVideoListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $timeout = 3600;
    public $tries = 3;

    public function handle(VideoUploaded $event)
    {
        Log::error('on listener');
        ProcessVideoJob::dispatch($event->lesson);
    }

    public function failed(VideoUploaded $event, \Throwable $exception)
    {
        $event->lesson->update([
            'processing_error' => 'Video upload processing failed: ' . $exception->getMessage(),
            'video_processed' => false,
        ]);
    }
}