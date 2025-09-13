<?php

namespace App\Domain\Income\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для преобразования модели Income в JSON-формат.
 */
class IncomeResource extends JsonResource
{
    /**
     * Преобразовать модель в массив для ответа JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'article_id' => $this->article_id,
            'office_id' => $this->office_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
