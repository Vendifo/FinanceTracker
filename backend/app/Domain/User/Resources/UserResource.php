<?php

namespace App\Domain\User\Resources;

use App\Domain\Role\Resources\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс пользователя.
 * Преобразует модель User в массив для JSON-ответа API.
 */
class UserResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     *
     * @param \Illuminate\Http\Request $request HTTP-запрос
     * @return array Ассоциативный массив с данными пользователя
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'role' => $this->role ? new RoleResource($this->role) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
