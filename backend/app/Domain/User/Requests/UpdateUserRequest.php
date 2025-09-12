<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        $user = $this->route('user');
        $id = $user instanceof \App\Models\User ? $user->id : $user;

        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'middle_name' => 'sometimes|nullable|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$id}",
            'password' => 'sometimes|nullable|string|min:6',
        ];
    }
}
