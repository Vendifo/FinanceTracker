<?php

namespace App\Interfaces\Repositories;

use App\Models\Role;

/**
 * Интерфейс репозитория для работы с ролями.
 * Определяет основные методы для CRUD операций.
 */
interface RoleRepositoryInterface
{
    /** Получить все роли */
    public function all();

    /** Найти роль по ID */
    public function find(int $id);

    /** Создать новую роль */
    public function create(array $data);

    /** Обновить роль */
    public function update(Role $role, array $data);

    /** Удалить роль */
    public function delete(Role $role);
}
