<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Lesson\Events\VideoUploaded;
use Modules\Lesson\Listeners\UploadedVideoListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {    
        Event::listen(
            VideoUploaded::class,
            UploadedVideoListener::class,
        );
    }
}
