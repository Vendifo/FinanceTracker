<?php

namespace App\Domain\Article\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидация запроса на создание статьи
 */
class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // любой авторизованный пользователь может создавать статьи
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100', // Название статьи обязательно
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название статьи обязательно',
            'name.max' => 'Название статьи не должно превышать 100 символов',
        ];
    }
}
