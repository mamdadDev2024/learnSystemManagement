<?php

namespace Modules\Interaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Interaction\Database\Factories\ViewFactory;

class View extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ip_address',
        'viewable_id',
        'viewable_type'
    ];

    public function viewable()
    {
        return $this->morphTo();
    }

    // protected static function newFactory(): ViewFactory
    // {
    //     // return ViewFactory::new();
    // }
}
