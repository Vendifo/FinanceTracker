<?php

namespace App\Domain\User\Controllers;

use App\Domain\User\Requests\StoreUserRequest;
use App\Domain\User\Requests\UpdateUserRequest;
use App\Domain\User\Services\UserServiceinterface;
use App\Domain\User\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Core\BaseController;

/**
 * Контроллер для управления пользователями.
 * Использует UserService для обработки бизнес-логики пользователей.
 */
class UserController extends BaseController
{
    /**
     * @var UserServiceinterface Сервис для работы с пользователями
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserServiceinterface $userService Сервис для работы с пользователями
     */
    public function __construct(UserServiceinterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Получить список всех пользователей.
     *
     * @return JsonResponse JSON с коллекцией пользователей и сообщением
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->all();
        return $this->apiResponse(UserResource::collection($users), 'Список пользователей');
    }

    /**
     * Создать нового пользователя.
     *
     * @param StoreUserRequest $request Запрос с данными для создания пользователя
     * @return JsonResponse JSON с данными нового пользователя и статусом 201
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return $this->apiResponse(new UserResource($user), 'Пользователь создан', true, 201);
    }

    /**
     * Показать одного пользователя по ID.
     *
     * @param int $id Идентификатор пользователя
     * @return JsonResponse JSON с данными пользователя или сообщением об ошибке
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
     * @param UpdateUserRequest $request Запрос с данными для обновления пользователя
     * @param int $id Идентификатор пользователя
     * @return JsonResponse JSON с обновлёнными данными пользователя или сообщением об ошибке
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
     * @param int $id Идентификатор пользователя
     * @return JsonResponse JSON с кодом 204 при успешном удалении или сообщением об ошибке
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->userService->delete($id);

        if (!$deleted) {
            return $this->apiResponse(null, 'Пользователь не найден', false, 404);
        }

        return response()->json(null, 204); // возвращаем JsonResponse с кодом 204
    }

    /**
     * Назначить роль пользователю.
     *
     * @param Request $request Запрос с идентификатором роли
     * @param User $user Пользователь, которому назначается роль
     * @return JsonResponse JSON с данными пользователя и сообщением
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = $this->userService->assignRole($user, $request->role_id);

        return $this->apiResponse($user, 'Роль назначена');
    }

    /**
     * Изменить пароль пользователя.
     *
     * @param Request $request Запрос с текущим и новым паролем
     * @param User $user Пользователь, чей пароль изменяется
     * @return JsonResponse JSON с сообщением об успешном изменении пароля
     */
    public function changePassword(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
            'current_password' => 'sometimes|string',
        ]);

        $this->userService->changePassword($user, $request->only('current_password', 'new_password'));

        return $this->apiResponse(null, 'Пароль успешно изменён');
    }
}
