<?php

namespace App\Domain\Income\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для создания нового дохода (Income).
 * Валидирует входные данные перед сохранением.
 */
class IncomeRequest extends FormRequest
{
    /**
     * Разрешение использования запроса.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Правила валидации входных данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|string|max:200',
            'amount' => 'required|numeric',
            'article_id' => 'required|exists:articles,id',
            'office_id' => 'required|exists:offices,id',
            'created_at' => 'nullable|date',
        ];
    }
}
