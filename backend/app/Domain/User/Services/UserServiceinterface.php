<?php

namespace App\Domain\User\Services;

use App\Models\User;

/**
 * Интерфейс сервиса пользователей.
 * Определяет основные методы работы с пользователями.
 */
interface UserServiceInterface
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
     * @param array $data Данные для создания
     * @return User
     */
    public function create(array $data);

    /**
     * Обновить существующего пользователя.
     *
     * @param int $id Идентификатор пользователя
     * @param array $data Данные для обновления
     * @return User|null Обновленный пользователь или null, если не найден
     */
    public function update($id, array $data);

    /**
     * Удалить пользователя.
     *
     * @param int $id Идентификатор пользователя
     * @return bool true, если удаление прошло успешно, false если пользователь не найден
     */
    public function delete($id);

    /**
     * Назначить пользователю роль.
     *
     * @param User $user Пользователь
     * @param int $roleId ID роли
     * @return User Пользователь с обновлённой ролью
     */
    public function assignRole(User $user, int $roleId): User;

    /**
     * Изменить пароль пользователя.
     *
     * @param User $user Пользователь
     * @param array $data Массив с ключами 'current_password' и 'new_password'
     * @return bool true, если пароль успешно изменён
     * @throws \Illuminate\Validation\ValidationException Если текущий пароль неверен
     */
    public function changePassword(User $user, array $data): bool;
}
