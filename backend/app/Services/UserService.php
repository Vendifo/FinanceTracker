<?php

namespace App\Services;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\UserServiceinterface;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceinterface
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->userRepository->find($id);
        if (!$user) return null;

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    public function delete($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) return false;

        $this->userRepository->delete($user);
        return true;
    }
}
