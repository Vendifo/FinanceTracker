<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

/**
 * Контроллер аутентификации пользователей
 *
 * Методы:
 * - register() - регистрация нового пользователя
 * - login() - вход и выдача токена
 * - me() - получение текущего пользователя
 * - logout() - удаление токена
 */
class AuthController extends ApiController
{
    /**
     * Регистрация нового пользователя
     *
     * Проверяем уникальность email и валидность пароля
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
            'Пользователь успешно зарегистрирован',
            true,
            201
        );
    }

    /**
     * Логин пользователя и выдача Sanctum токена
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->apiResponse(
                null,
                'Неверный email или пароль',
                false,
                401
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->apiResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Вход выполнен успешно'
        );
    }

    /**
     * Получение текущего авторизованного пользователя
     * Требует middleware 'auth:sanctum'
     */
    public function me(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return $this->apiResponse(
                null,
                'Не авторизован',
                false,
                401
            );
        }

        return $this->apiResponse(
            ['user' => new UserResource($request->user())],
            'Текущий пользователь'
        );
    }

    /**
     * Logout пользователя
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->apiResponse(
                null,
                'Не авторизован',
                false,
                401
            );
        }

        $user->currentAccessToken()?->delete();

        return $this->apiResponse(
            null,
            'Выход выполнен успешно'
        );
    }
}
