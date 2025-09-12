<?php

namespace App\Domain\Office\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для создания нового офиса
 *
 * Валидирует входные данные для создания офиса.
 */
class StoreOfficeRequest extends FormRequest
{
    /**
     * Разрешить выполнение запроса
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации запроса
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
        ];
    }
}
