<?php

namespace Modules\Interaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Interaction\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'text',
        'commentable_id',
        'commentable_type'
    ];

    public function commentable()
    {
        return $this->morphEagerTo();
    }
    // protected static function newFactory(): CommentFactory
    // {
    //     // return CommentFactory::new();
    // }
}
