<?php

namespace Modules\Interaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Interaction\Database\Factories\LikeFactory;

class Like extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["ip_address", "likable_id", "likable_type"];

    public function likable()
    {
        return $this->morphTo();
    }

    // protected static function newFactory(): LikeFactory
    // {
    //     // return LikeFactory::new();
    // }
}
