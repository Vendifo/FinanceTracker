<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидация запроса на обновление статьи
 */

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // любой авторизованный пользователь может обновлять статью
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100' // Название статьи обязательно
        ];
    }
}
