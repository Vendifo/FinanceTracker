<?php

namespace App\Domain\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс запроса для создания нового пользователя.
 * Определяет правила валидации для полей формы.
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array Правила валидации
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

            // Дополнительные необязательные поля
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
        ];
    }
}
