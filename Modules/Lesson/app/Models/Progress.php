<?php

namespace Modules\Lesson\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lesson\Database\Factories\ProgressFactory;
use Modules\User\Models\User;

// use Modules\Lesson\Database\Factories\ProgressFactory;

class Progress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "user_id",
        "lesson_id",
        "is_completed",
        "progress_percentage",
        "started_at",
        "completed_at",
        "time_spent",
        "last_accessed_at",
    ];

    protected function casts()
    {
        return [
            "is_completed" => "boolean",
            "progress_percentage" => "integer",
            "time_spent" => "integer",
            "started_at" => "datetime",
            "completed_at" => "datetime",
            "last_accessed_at" => "datetime",
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    protected static function newFactory(): ProgressFactory
    {
        // return ProgressFactory::new();
    }
}
