<?php

namespace Modules\Lesson\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Course\Models\Course;
use Modules\Interaction\Models\Comment;
use Modules\Interaction\Models\Like;
use Modules\Interaction\Models\View;
use Modules\Lesson\Database\Factories\LessonFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'order',
        'attachment_url',
        'attachment_name',
        'video_url',
        'video_name',
        'course_id',
        'is_published',
        'duration',
        'slug'
    ];

    protected $casts = [
        'order' => 'integer',
        'is_published' => 'boolean',
        'duration' => 'integer', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->order)) {
                $lesson->order = static::where('course_id', $lesson->course_id)
                    ->max('order') + 1;
            }
            
            if (empty($lesson->slug)) {
                $lesson->slug = \Illuminate\Support\Str::slug($lesson->title);
            }
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class , 'likeable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class , 'commentable');
    }

    public function views()
    {
        return $this->morphMany(View::class , 'viewable');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(\Modules\Lesson\Models\LessonProgress::class);
    }

    /**
     * Scope a query to only include published lessons.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to order lessons by their order.
     */
    public function scopeOrdered($query, $direction = 'asc')
    {
        return $query->orderBy('order', $direction);
    }

    /**
     * Get the next lesson in the course.
     */
    public function getNextLessonAttribute()
    {
        return static::where('course_id', $this->course_id)
            ->where('order', '>', $this->order)
            ->ordered()
            ->first();
    }

    /**
     * Get the previous lesson in the course.
     */
    public function getPreviousLessonAttribute()
    {
        return static::where('course_id', $this->course_id)
            ->where('order', '<', $this->order)
            ->ordered('desc')
            ->first();
    }

    /**
     * Check if the lesson has an attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_url);
    }

    /**
     * Check if the lesson has a video.
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return 'N/A';
        }

        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%dh %02dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): LessonFactory
    {
        return LessonFactory::new();
    }
}