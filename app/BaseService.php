<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseService
{
    protected function execute(callable $callback, string $errorMessage = 'Service error', bool $useTransaction = true): ServiceResponse
    {
        try {
            if ($useTransaction) {
                return DB::transaction($callback);
            }

            return $callback();

        } catch (Throwable $e) {
            Log::error($errorMessage, [
                'exception' => $e,
                'trace'     => $e->getTraceAsString(),
                'user_id'   => auth()->id() ?? null,
            ]);

            report($e);

            return ServiceResponse::error(
                $errorMessage,
                config('app.debug') ? $e->getMessage() : null
            );
        }
    }
}
