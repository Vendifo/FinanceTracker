<?php

namespace App\Domain\Office\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для представления офиса в формате JSON
 *
 * Преобразует модель Office в JSON-формат для API.
 */
class OfficeResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            /** @var int ID офиса */
            'id' => $this->id,

            /** @var string Название офиса */
            'name' => $this->name,

            /** @var \Illuminate\Support\Carbon Дата и время создания записи */
            'created_at' => $this->created_at,

            /** @var \Illuminate\Support\Carbon Дата и время последнего обновления записи */
            'updated_at' => $this->updated_at,
        ];
    }
}
