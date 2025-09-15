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
    public $queue = 'video';
    
    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function handle()
    {
        Log::error('on compress job');
        try {
            if (!Storage::exists($this->lesson->video_path)) {
                throw new \Exception("Resized video not found");
            }

            $resizedPath = $this->lesson->video_path;
            $filename = pathinfo($resizedPath, PATHINFO_FILENAME);
            $compressedPath = "lessons/videos/compressed/{$filename}.mp4";

            $format = new X264('aac');
            $format->setKiloBitrate(800);
            $format->setAdditionalParameters(['-crf', '28']);

            FFmpeg::fromDisk('local')
                ->open($resizedPath)
                ->export()
                ->toDisk('local')
                ->inFormat($format)
                ->save($compressedPath);

            $this->lesson->update([
                'video_path' => $compressedPath,
                'video_compressed' => true,
                'original_video_size' => Storage::size($resizedPath),
                'compressed_video_size' => Storage::size($compressedPath),
            ]);

            Storage::delete($resizedPath);

            Log::info("Video compressed successfully for lesson: {$this->lesson->id}");

        } catch (\Exception $e) {
            Log::error("CompressVideoJob failed: " . $e->getMessage());
            $this->fail($e);
        }
    }

    public function failed(\Throwable $exception)
    {
        $this->lesson->update([
            'processing_error' => 'Compression failed: ' . $exception->getMessage(),
            'video_processed' => false,
        ]);
    }
}