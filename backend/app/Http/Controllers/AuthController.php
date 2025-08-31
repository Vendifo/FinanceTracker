<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Контроллер для аутентификации пользователей.
 * Включает регистрацию, логин, logout и получение текущего пользователя.
 */
class AuthController extends Controller
{
    /**
     * Универсальный метод формирования JSON-ответа API.
     *
     * @param mixed $data Данные для ответа
     * @param string $message Сообщение
     * @param bool $success Статус успеха
     * @param int $status HTTP-код
     * @return JsonResponse
     */
    private function apiResponse($data = null, string $message = '', bool $success = true, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Регистрация нового пользователя.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->apiResponse(
            ['user' => new UserResource($user)],
            'Пользователь успешно создан',
            true,
            201
        );
    }

    /**
     * Логин пользователя и выдача токена.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->apiResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Успешный вход'
        );
    }

    /**
     * Получение текущего авторизованного пользователя.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return $this->apiResponse(
            ['user' => new UserResource($request->user())]
        );
    }

    /**
     * Logout — удаление текущего токена пользователя.
     *
     * @param Request $request
     * @return Response Возвращает пустой ответ с кодом 204
     */
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
