<?php

namespace App\Domain\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс запроса для работы с финансовыми фильтрами.
 *
 * Используется для валидации входных данных при запросах
 * финансовых показателей (доходы, расходы, баланс) по офисам,
 * статьям, пользователям и периодам.
 */
class FinanceRequest extends FormRequest
{
    /**
     * Определяет правила валидации запроса.
     *
     * @return array Массив правил валидации
     */
    public function rules(): array
    {
        return [
            // Фильтр по конкретному офису (необязательный)
            'office_id' => 'nullable|exists:offices,id',

            // Фильтр по конкретной статье (необязательный)
            'article_id' => 'nullable|exists:articles,id',

            // Фильтр по конкретному пользователю (необязательный)
            'user_id' => 'nullable|exists:users,id',

            // Фильтр по году (целое число от 1900 до 2100)
            'year' => 'nullable|integer|min:1900|max:2100',

            // Фильтр по месяцу (целое число от 1 до 12)
            'month' => 'nullable|integer|min:1|max:12',

            // Фильтр по дню месяца (целое число от 1 до 31)
            'day' => 'nullable|integer|min:1|max:31',

            // Фильтр по конкретной дате
            'date' => 'nullable|date',

            // Начало периода для диапазона дат
            'date_from' => 'nullable|date',

            // Конец периода для диапазона дат
            'date_to' => 'nullable|date',
        ];
    }
}
