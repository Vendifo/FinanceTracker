<?php

namespace App\Domain\Office\Repositories;

use App\Models\Office;

/**
 * Репозиторий для работы с моделью Office
 */
class OfficeRepository implements OfficeRepositoryInterface
{

    protected OfficeRepositoryInterface $officeRepository;

    public function __construct(OfficeRepositoryInterface $officeRepository)
    {
        $this->officeRepository = $officeRepository;
    }

    /**
     * Получить все офисы
     *
     * @return \Illuminate\Database\Eloquent\Collection|Office[]
     */
    public function all()
    {
        return Office::all();
    }

    /**
     * Найти офис по ID
     *
     * @param int $id
     * @return Office|null
     */
    public function find($id)
    {
        return Office::find($id);
    }

    /**
     * Создать новый офис
     *
     * @param array $data Данные для создания офиса
     * @return Office
     */
    public function create(array $data)
    {
        return Office::create($data);
    }

    /**
     * Обновить существующий офис
     *
     * @param Office $office Объект офиса для обновления
     * @param array $data Данные для обновления
     * @return Office Обновленный объект офиса
     */
    public function update(Office $office, array $data)
    {
        $office->update($data);
        return $office;
    }

    /**
     * Удалить офис
     *
     * @param Office $office Объект офиса для удаления
     * @return bool|null
     */
    public function delete(Office $office)
    {
        return $office->delete();
    }
}
