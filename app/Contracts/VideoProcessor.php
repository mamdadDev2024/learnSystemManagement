<?php

namespace App\Services;

use ProtoneMedia\LaravelFFmpeg\Support\FFmpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;

class VideoProcessor
{
    public function processVideo($inputPath, $outputPath, $options = [])
    {
        $format = new X264();
        $format->setAudioCodec("aac");

        $ffmpeg = FFmpeg::fromDisk("local")->open($inputPath);

        $export = $ffmpeg->export()->toDisk("local")->inFormat($format);

        if (isset($options["width"]) && isset($options["height"])) {
            $export->resize($options["width"], $options["height"]);
        }

        if (isset($options["video_bitrate"])) {
            $format->setKiloBitrate($options["video_bitrate"]);
        }

        return $export->save($outputPath);
    }

    public function generateThumbnail(
        $videoPath,
        $thumbnailPath,
        $timeInSeconds = 10,
    ) {
        FFmpeg::fromDisk("local")
            ->open($videoPath)
            ->getFrameFromSeconds($timeInSeconds)
            ->export()
            ->toDisk("local")
            ->save($thumbnailPath);
    }

    public function getVideoInfo($videoPath)
    {
        $video = FFmpeg::fromDisk("local")->open($videoPath);

        return [
            "duration" => $video->getDurationInSeconds(),
            "format" => $video->getFormat(),
            "dimensions" => $video->getStreams()->first()->getDimensions(),
            "bitrate" => $video->getStreams()->first()->get("bit_rate"),
        ];
    }
}
