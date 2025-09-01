<?php

namespace App\Services;

use App\Interfaces\Services\RoleServiceInterface;
use App\Repositories\RoleRepository;
use App\Models\Role;

/**
 * Сервисный слой для работы с ролями.
 * Отвечает за бизнес-логику и взаимодействие с репозиторием.
 */
class RoleService implements RoleServiceInterface
{
    /** @var RoleRepository Репозиторий для работы с ролями */
    protected RoleRepository $roleRepository;

    /**
     * Конструктор
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Получить все роли
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->roleRepository->all();
    }

    /**
     * Найти роль по ID
     *
     * @param int $id
     * @return Role|null
     */
    public function find(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Создать новую роль
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Обновить существующую роль
     *
     * @param int $id
     * @param array $data
     * @return Role|null
     */
    public function update($id, $data)
    {
        $role = $this->roleRepository->find($id);
        if (!$role) {
            return null;
        }

        return $this->roleRepository->update($role, $data);
    }

    /**
     * Удалить роль по ID
     *
     * @param int $id
     * @return bool true, еси удаление успешное , false если роли не существует
     */
    public function delete($id)

    {
        $role = $this->roleRepository->find($id);
        if (!$role) {
            return false;
        }

        return $this->roleRepository->delete($role);
    }
}
