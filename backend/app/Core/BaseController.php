<?php

namespace App\Core;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Универсальный JSON-ответ
     */
    protected function apiResponse(
        mixed $data = null,
        string $message = '',
        bool $success = true,
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Быстрый ответ для успешного результата
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return $this->apiResponse($data, $message, true, $status);
    }

    /**
     * Быстрый ответ для ошибки
     */
    protected function errorResponse(
        string $message = 'Error',
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
