<?php
namespace App\Commons\Traits;

use Illuminate\Http\JsonResponse;

trait BaseApiResponse
{
    public function apiSuccess($statusCode = 200, $message = null, $data = null): JsonResponse
    {
        return response()->json([
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function apiError($statusCode = 500, $message = null, $errors = []): JsonResponse
    {
        return response()->json([
            'code' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
