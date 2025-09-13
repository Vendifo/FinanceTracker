<?php

namespace App\Domain\User\Repositories;

use App\Models\User;

/**
 * Интерфейс репозитория пользователей.
 * Определяет методы для работы с данными пользователей.
 */
interface UserRepositoryInterface
{
    /**
     * Получить всех пользователей.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function all();

    /**
     * Найти пользователя по ID.
     *
     * @param int $id Идентификатор пользователя
     * @return User|null
     */
    public function find($id);

    /**
     * Создать нового пользователя.
     *
     * @param array $data Данные для создания пользователя
     * @return User
     */
    public function create(array $data);

    /**
     * Обновить данные существующего пользователя.
     *
     * @param User $user Пользователь для обновления
     * @param array $data Новые данные
     * @return User Обновленный пользователь
     */
    public function update(User $user, array $data);

    /**
     * Удалить пользователя.
     *
     * @param User $user Пользователь для удаления
     * @return bool|null
     */
    public function delete(User $user);
}
