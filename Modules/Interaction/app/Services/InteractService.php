<?php

namespace Modules\Interaction\Services;

use App\Contracts\BaseService;

class InteractService extends BaseService
{
    public function recordView($viewable)
    {
        if (
            auth()->check() &&
            $viewable
                ->views()
                ->where("user_id", auth()->id())
                ->exists()
        ) {
            return;
        }
        $viewable->views()->create([
            "ip_address" => request()->ip(),
            "user_id" => auth()->check() ? auth()->id() : null,
        ]);
    }
}
