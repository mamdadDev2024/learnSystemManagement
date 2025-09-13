<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Enrollment\Models\Enrollment;
use Modules\Interaction\Models\Comment;
use Modules\Interaction\Models\Like;
use Modules\Interaction\Models\View;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;

// use Modules\Course\Database\Factories\CourseFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "title",
        "slug",
        "description",
        "price",
        "user_id",
        "published",
    ];



    protected function casts()
    {
        return [
            "published" => "boolean",
        ];
    }

    public function getCourseProgressAttribute()
    {
        $totalLessons = $this->lessons()->count();

        $completedLessons = $this->lessons()
            ->whereHas('progress', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('is_completed', true);
            })
            ->count();

    }

    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, "enrollments");
    }

    public function likes()
    {
        return $this->morphMany(Like::class, "likeable");
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, "commentable");
    }

    public function views()
    {
        return $this->morphMany(View::class, "viewable");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    // protected static function newFactory(): CourseFactory
    // {
    //     // return CourseFactory::new();
    // }
}
