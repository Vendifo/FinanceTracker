<?php

namespace App\Domain\Office\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для обновления существующего офиса
 *
 * Валидирует входные данные при обновлении офиса.
 */
class UpdateOfficeRequest extends FormRequest
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
