<?php

namespace App\Domain\Office\Repositories;

use App\Models\Office;

/**
 * Интерфейс репозитория для работы с офисами
 */
interface OfficeRepositoryInterface
{
    /**
     * Получить все офисы
     *
     * @return \Illuminate\Database\Eloquent\Collection|Office[]
     */
    public function all();

    /**
     * Найти офис по ID
     *
     * @param int $id
     * @return Office|null
     */
    public function find($id);

    /**
     * Создать новый офис
     *
     * @param array $data Данные для создания офиса
     * @return Office
     */
    public function create(array $data);

    /**
     * Обновить существующий офис
     *
     * @param Office $office Объект офиса для обновления
     * @param array $data Данные для обновления
     * @return Office Обновленный объект офиса
     */
    public function update(Office $office, array $data);

    /**
     * Удалить офис
     *
     * @param Office $office Объект офиса для удаления
     * @return bool|null
     */
    public function delete(Office $office);
}
