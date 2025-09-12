<?php

namespace App\Domain\User\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
/**
 * Сервис пользователей.
 * Реализует логику работы с пользователями, включая CRUD, смену пароля и назначение ролей.
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Получить всех пользователей.
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function all()
    {
        return $this->userRepository->all();
    }

    /**
     * Найти пользователя по ID.
     *
     * @param int $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Создать нового пользователя.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    /**
     * Обновить существующего пользователя.
     *
     * @param int $id
     * @param array $data
     * @return User|null
     */
    public function update($id, array $data)
    {
        $user = $this->userRepository->find($id);
        if (!$user) return null;

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Удалить пользователя.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) return false;

        $this->userRepository->delete($user);
        return true;
    }

    /**
     * Назначить пользователю роль.
     *
     * @param User $user
     * @param int $roleId
     * @return User
     */
    public function assignRole(User $user, int $roleId): User
    {
        $role = Role::findOrFail($roleId);
        $user->role()->associate($role);
        $user->save();

        return $user->fresh();
    }

    /**
     * Изменить пароль пользователя.
     *
     * @param User $user
     * @param array $data ['current_password' => string, 'new_password' => string]
     * @return bool
     * @throws ValidationException Если текущий пароль неверен
     */
    public function changePassword(User $user, array $data): bool
    {
        if ($user->id === Auth::id() && isset($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                throw ValidationException::withMessages(['current_password' => 'Неверный текущий пароль']);
            }
        }

        $user->password = Hash::make($data['new_password']);
        return $user->save();
    }
}
