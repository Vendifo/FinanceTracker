<?php

namespace App\Domain\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс запроса для обновления существующего пользователя.
 * Определяет правила валидации для полей формы.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     * Учитывает уникальность email с исключением текущего пользователя.
     *
     * @return array Правила валидации
     */
    public function rules(): array
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
