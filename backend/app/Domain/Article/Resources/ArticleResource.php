<?php

namespace App\Domain\Article\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ArticleResource
 *
 * Отвечает за форматирование JSON-ответа
 * Включает связь с доходами и расходами
 */
class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
