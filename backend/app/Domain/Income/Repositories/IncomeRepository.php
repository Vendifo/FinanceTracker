<?php

namespace App\Domain\Income\Repositories;

use App\Models\Income;

/**
 * Репозиторий для работы с моделью Income.
 * Реализует базовые CRUD операции.
 */
class IncomeRepository implements IncomeRepositoryInterface
{
    /**
     * Получить все доходы.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Income::all();
    }

    /**
     * Найти доход по ID.
     *
     * @param int $id
     * @return Income|null
     */
    public function find($id)
    {
        return Income::find($id);
    }

    /**
     * Создать новый доход.
     *
     * @param array $data
     * @return Income
     */
    public function create(array $data)
    {
        return Income::create($data);
    }

    /**
     * Обновить существующий доход.
     *
     * @param Income $income
     * @param array $data
     * @return Income
     */
    public function update(Income $income, array $data)
    {
        $income->update($data);
        return $income;
    }

    /**
     * Удалить доход.
     *
     * @param Income $income
     * @return bool
     */
    public function delete(Income $income)
    {
        return $income->delete();
    }
}
