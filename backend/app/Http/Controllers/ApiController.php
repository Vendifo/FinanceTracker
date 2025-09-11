<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class ApiController extends BaseController
{
    /**
     * Универсальный JSON-ответ
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
