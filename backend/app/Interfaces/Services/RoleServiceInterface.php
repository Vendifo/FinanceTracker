<?php

namespace App\Interfaces\Services;

/**
 * Интерфейс сервиса для работы с ролями.
 * Определяет методы бизнес-логики CRUD для ролей.
 */
interface RoleServiceInterface
{
    /** Получить все роли */
    public function all();

    /** Найти роль по ID */
    public function find(int $id);

    /** Создать новую роль */
    public function create(array $data);

    /** Обновить роль */
    public function update($id, array $data);

    /** Удалить роль */
    public function delete($id);
}
