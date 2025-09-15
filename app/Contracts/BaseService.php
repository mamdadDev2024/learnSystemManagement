<?php

namespace App\Contracts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseService
{
    protected function execute(
        callable $callback,
        string $errorMessage = "Internal Server Error!",
        string $successMessage = "Operation Completed Successfully!",
        bool $useTransaction = true,
    ): ServiceResponse {
        try {
            $result = $useTransaction
                ? DB::transaction($callback)
                : $callback();

            return ServiceResponse::success($result , $successMessage);
        } catch (Throwable $e) {
            Log::error($errorMessage, [
                "exception" => $e,
                "trace" => $e->getTraceAsString(),
                "user_id" => auth()->id() ?? null,
            ]);

            report($e);

            return ServiceResponse::error(
                $errorMessage,
                env("APP_DEBUG") ? $e->getMessage() : null,
            );
        }
    }
}
