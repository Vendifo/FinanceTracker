<?php

namespace App\Domain\Office\Services;

use App\Models\Office;
use App\Domain\Office\Repositories\OfficeRepositoryInterface;

/**
 * Сервис для работы с офисами
 */
class OfficeService implements OfficeServiceInterface
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
        return $this->officeRepository->all();
    }

    /**
     * Найти офис по ID
     *
     * @param int $id
     * @return Office|null
     */
    public function find($id)
    {
        return $this->officeRepository->find($id);
    }

    /**
     * Создать новый офис
     *
     * @param array $data
     * @return Office
     */
    public function create(array $data)
    {
        return $this->officeRepository->create($data);
    }

    /**
     * Обновить офис по ID
     *
     * @param int $id
     * @param array $data
     * @return Office|null
     */
    public function update($id, array $data)
    {
        $office = $this->officeRepository->find($id);
        if (!$office) {
            return null;
        }

        return $this->officeRepository->update($office, $data);
    }

    /**
     * Удалить офис по ID
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $office = $this->officeRepository->find($id);
        if (!$office) {
            return false;
        }

        return $this->officeRepository->delete($office);
    }
}
