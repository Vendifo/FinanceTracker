<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\RoleServiceInterface;
use Illuminate\Http\Request;

/**
 * Контроллер для работы с ролями.
 * Выполняет CRUD операции через сервисный слой.
 */
class RoleController extends BaseController
{
    /**
     * @var RoleServiceInterface
     */
    protected RoleServiceInterface $roleService;

    /**
     * RoleController constructor.
     *
     * @param RoleServiceInterface $roleService
     */
    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Получить список всех ролей.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->roleService->all());
    }

    /**
     * Показать одну роль по ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $role = $this->roleService->find($id);
        if (!$role) return response()->json(['message' => 'Role not found'], 404);

        return response()->json($role, 200);
    }

    /**
     * Создать новую роль.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $role = $this->roleService->create($request->only('name'));

        return response()->json($role, 201);
    }

    /**
     * Обновить существующую роль.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $role = $this->roleService->update($id, $request->only('name'));

        if (!$role) return response()->json(['message' => 'Role not found'], 404);
        return response()->json($role, 200);
    }

    /**
     * Удалить роль по ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->roleService->delete($id);
        if (!$deleted) return response()->json(['message' => 'Role not found'], 404);

        return response()->json(['message' => 'Role deleted'], 200);
    }
}
