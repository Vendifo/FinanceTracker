<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * Репозиторий для работы с пользователями.
 * Реализует методы интерфейса UserRepositoryInterface.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Получить всех пользователей.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Найти пользователя по ID.
     *
     * @param int $id Идентификатор пользователя
     * @return User|null
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Создать нового пользователя.
     *
     * @param array $data Данные для создания
     * @return User
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Обновить данные существующего пользователя.
     *
     * @param User $user Пользователь для обновления
     * @param array $data Новые данные
     * @return User Обновленный пользователь
     */
    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    /**
     * Удалить пользователя.
     *
     * @param User $user Пользователь для удаления
     * @return bool|null
     */
    public function delete(User $user)
    {
        return $user->delete();
    }
}
