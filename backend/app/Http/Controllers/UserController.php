<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\Services\UserServiceinterface;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Контроллер для управления пользователями.
 * Использует UserService для бизнес-логики.
 */
class UserController extends Controller
{
    /**
     * @var UserServiceinterface Сервис для работы с пользователями
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserServiceinterface $userService
     */
    public function __construct(UserServiceinterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Универсальный метод формирования JSON-ответа для API.
     *
     * @param mixed $data Данные для ответа
     * @param string $message Сообщение
     * @param bool $success Статус успеха
     * @param int $status HTTP-код ответа
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
     * Получить список всех пользователей.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->all();
        return $this->apiResponse(UserResource::collection($users), 'Список пользователей');
    }

    /**
     * Создать нового пользователя.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return $this->apiResponse(new UserResource($user), 'Пользователь создан', true, 201);
    }

    /**
     * Показать одного пользователя по ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return $this->apiResponse(null, 'Пользователь не найден', false, 404);
        }

        return $this->apiResponse(new UserResource($user), 'Пользователь найден');
    }

    /**
     * Обновить существующего пользователя.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->update($id, $request->validated());

        if (!$user) {
            return $this->apiResponse(null, 'Пользователь не найден', false, 404);
        }

        return $this->apiResponse(new UserResource($user), 'Пользователь обновлен');
    }

    /**
     * Удалить пользователя по ID.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->userService->delete($id);

        if (!$deleted) {
            return $this->apiResponse(null, 'Пользователь не найден', false, 404);
        }

        return response()->json(null, 204); // возвращаем JsonResponse с кодом 204
    }
}
