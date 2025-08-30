<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\Services\UserServiceinterface;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceinterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->all());
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return response()->json($user, 201);

    }

    public function show($id)
    {
        $user = $this->userService->find($id);
        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($id, $request->validated());
        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);

        }
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = $this->userService->delete($id);

        if (!$user)
        {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }
        return response()->json(['message' => 'Пользователь удален']);
    }
}
