<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Универсальный JSON-ответ
     *
     * @param mixed $data
     * @param string $message
     * @param bool $success
     * @param int $status
     * @return JsonResponse
     */
    protected function apiResponse($data = null, string $message = '', bool $success = true, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
