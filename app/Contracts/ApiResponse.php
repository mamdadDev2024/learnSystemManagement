<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $statusCode);
    }

    public static function error(string $message = 'Error', $errors = null, int $statusCode = 400)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $statusCode);
    }

    public static function validation($errors, string $message = 'Validation Error', int $statusCode = 422)
    {
        return response()->json([
            'status'  => 'validation_error',
            'message' => $message,
            'errors'  => $errors
        ], $statusCode);
    }

    protected function paginatedResponse(
        $paginator,
        string $message = 'Success'
    ): JsonResponse {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }
}
