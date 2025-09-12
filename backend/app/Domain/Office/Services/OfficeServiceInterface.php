<?php

namespace App\Domain\Office\Services;

/**
 * Интерфейс сервиса офисов
 */
interface OfficeServiceInterface
{
    /**
     * Получить все офисы
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Найти офис по ID
     *
     * @param int $id
     * @return \App\Models\Office|null
     */
    public function find($id);

    /**
     * Создать офис
     *
     * @param array $data
     * @return \App\Models\Office
     */
    public function create(array $data);

    /**
     * Обновить офис по ID
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Office|null
     */
    public function update($id, array $data);

    /**
     * Удалить офис по ID
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);
}
