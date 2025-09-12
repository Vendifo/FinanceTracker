<?php

namespace App\Domain\Role\Services;

use App\Domain\Role\Services\RoleServiceInterface;
use App\Domain\Role\Repositories\RoleRepository;
use App\Models\Role;
use App\Domain\Role\Repositories\RoleRepositoryInterface;

/**
 * Сервисный слой для работы с ролями.
 * Отвечает за бизнес-логику и взаимодействие с репозиторием.
 */
class RoleService implements RoleServiceInterface
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
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
    public function find($id): ?Role
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
