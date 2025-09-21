<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Auth\Requests\LoginRequest;
use App\Domain\Auth\Requests\RegisterRequest;
use App\Domain\User\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Core\BaseController;

/**
 * Контроллер аутентификации пользователей
 *
 * Методы:
 * - register() - регистрация нового пользователя
 * - login() - вход и выдача токена
 * - me() - получение текущего пользователя
 * - logout() - удаление токена
 */
class AuthController extends BaseController
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
            'User successfully registered',
            true,
            201
        );
    }

    /**
     * Логин пользователя и выдача Sanctum токена
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Ищем пользователя по email или username
        $user = User::where('email', $request->email)
            ->orWhere('name', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->apiResponse(
                null,
                'Invalid email or username or password',
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
            'Login successful'
        );
    }



    /**
     * Получение текущего авторизованного пользователя
     * Требует middleware 'auth:sanctum'
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->apiResponse(
                null,
                'Not authenticated',
                false,
                401
            );
        }

        return $this->apiResponse(
            ['user' => new UserResource($user)],
            'Current user',
            true
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
                'Not authenticated',
                false,
                401
            );
        }

        $user->currentAccessToken()?->delete();

        return $this->apiResponse(
            null,
            'Logout successful',
            true
        );
    }
}
