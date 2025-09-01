<?php

namespace App\Repositories;

use App\Interfaces\Repositories\RoleRepositoryInterface;
use App\Models\Role;

/**
 * Репозиторий для работы с моделями Role.
 * Реализует CRUD операции.
 */
class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Получить все роли
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Role::all();
    }

    /**
     * Найти роль по ID
     *
     * @param int $id
     * @return Role|null
     */
    public function find(int $id)
    {
        return Role::find($id);
    }

    /**
     * Создать новую роль
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data)
    {
        return Role::create($data);
    }

    /**
     * Обновить существующую роль
     *
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update(Role $role, array $data)
    {
        $role->update($data);
        return $role;
    }

    /**
     * Удалить роль
     *
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
