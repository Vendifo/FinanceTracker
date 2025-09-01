<?php

namespace App\Interfaces\Services;


use App\Models\User;
interface UserServiceinterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    /**
     * Назначить пользователю роль.
     *
     * @param User $user
     * @param int $roleId
     * @return User
     */
    public function assignRole(User $user, int $roleId): User;
}
