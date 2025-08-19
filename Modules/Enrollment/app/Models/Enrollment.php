<?php

namespace Modules\Enrollment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Models\Course;
use Modules\User\Models\User;

// use Modules\Enrollment\Database\Factories\EnrollmentFactory;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'status'
    ];


    public function course()
    {
        return $this->hasOne(Course::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // protected static function newFactory(): EnrollmentFactory
    // {
    //     // return EnrollmentFactory::new();
    // }
}
