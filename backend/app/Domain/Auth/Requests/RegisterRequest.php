<?php

namespace App\Domain\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    // Разрешаем всем пользователям делать запрос
    public function authorize(): bool
    {
        return true;
    }

    // Правила валидации
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    // Кастомные сообщения об ошибках
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не может быть длиннее 255 символов',
            'email.required' => 'Email обязателен',
            'email.email' => 'Email должен быть корректным',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Пароль должен быть не меньше 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
