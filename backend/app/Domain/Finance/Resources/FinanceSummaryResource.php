<?php

namespace App\Domain\Finance\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для форматирования финансовой сводки.
 *
 * Преобразует данные доходов, расходов и баланса для API,
 * включая примененные фильтры.
 */
class FinanceSummaryResource extends JsonResource
{
    /**
     * Преобразует ресурс в массив для JSON-ответа.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            // Примененные фильтры (офис, статья, период и т.д.)
            'filters' => $this['filters'] ?? [],

            // Общая сумма доходов
            'income' => $this['income'] ?? 0,

            // Общая сумма расходов
            'expense' => $this['expense'] ?? 0,

            // Баланс = доходы - расходы
            'balance' => $this['balance'] ?? 0,
        ];
    }
}
