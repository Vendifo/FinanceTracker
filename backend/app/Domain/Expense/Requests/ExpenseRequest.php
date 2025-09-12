<?php

namespace App\Domain\Expense\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для создания и обновления расходов
 *
 * @package App\Http\Requests
 */
class ExpenseRequest extends FormRequest
{
    /**
     * Авторизация запроса
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Либо логика авторизации
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'description' => 'required|string|max:200',
            'amount'      => 'required|numeric',
            'user_id'     => 'required|exists:users,id',
            'article_id'  => 'required|exists:articles,id',
            'office_id'   => 'required|exists:offices,id',
            'created_at'  => 'sometimes|date',
        ];

        // Для обновления делаем все поля необязательными
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as &$rule) {
                $rule = str_replace('required', 'sometimes', $rule);
            }
        }

        return $rules;
    }
}
