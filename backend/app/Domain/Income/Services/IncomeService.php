<?php

namespace App\Domain\Income\Services;

use App\Domain\Income\Repositories\IncomeRepositoryInterface;

/**
 * Сервис для работы с доходами.
 * Инкапсулирует бизнес-логику, делегируя работу репозиторию.
 */
class IncomeService implements IncomeServiceInterface
{
    protected IncomeRepositoryInterface $incomeRepository;

    public function __construct(IncomeRepositoryInterface $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }


    /**
     * Получить все доходы.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->incomeRepository->all();
    }

    /**
     * Найти доход по ID.
     *
     * @param int $id
     * @return \App\Models\Income|null
     */
    public function find($id)
    {
        return $this->incomeRepository->find($id);
    }

    /**
     * Создать новый доход.
     *
     * @param array $data
     * @return \App\Models\Income
     */
    public function create(array $data)
    {
        return $this->incomeRepository->create($data);
    }

    /**
     * Обновить существующий доход по ID.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Income|null
     */
    public function update($id, array $data)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return null;
        }

        return $this->incomeRepository->update($income, $data);
    }

    /**
     * Удалить доход по ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return false;
        }

        return $this->incomeRepository->delete($income);
    }
}
