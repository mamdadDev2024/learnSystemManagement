<?php

namespace Modules\Lesson\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Lesson\Models\Lesson;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'video';
    public $lesson;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function handle()
    {
        Log::error('on proccess job');
        Bus::chain([
            new ResizeVideoJob($this->lesson),
            new CompressVideoJob($this->lesson),
        ])->catch(function (\Throwable $e) {
            $this->lesson->update([
                'processing_error' => 'Video processing chain failed: ' . $e->getMessage(),
                'video_processed' => false,
            ]);
            \Log::error("Video processing chain failed: " . $e->getMessage());
        })->dispatch();
    }
}