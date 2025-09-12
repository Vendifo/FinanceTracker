<?php

namespace App\Domain\Finance\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinanceSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'filters' => $this['filters'] ?? [],
            'income' => $this['income'] ?? 0,
            'expense' => $this['expense'] ?? 0,
            'balance' => $this['balance'] ?? 0,
        ];
    }
}
