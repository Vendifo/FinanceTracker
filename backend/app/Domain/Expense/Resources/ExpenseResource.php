<?php

namespace App\Domain\Expense\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для представления расхода
 *
 * @package App\Http\Resources
 */
class ExpenseResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'amount'      => $this->amount,
            'user_id'     => $this->user_id,
            'article_id'  => $this->article_id,
            'office_id'   => $this->office_id,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
