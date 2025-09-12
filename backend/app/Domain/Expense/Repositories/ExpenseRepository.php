<?php

namespace App\Domain\Expense\Repositories;

use App\Domain\Expense\Repositories\ExpenseRepositoryInterface;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для работы с моделью расходов
 *
 * @package App\Repositories
 */
class ExpenseRepository implements ExpenseRepositoryInterface
{
    /**
     * Получить все расходы
     *
     * @return Collection|Expense[]
     */
    public function all()
    {
        return Expense::all();
    }

    /**
     * Найти расход по ID
     *
     * @param int $id
     * @return Expense|null
     */
    public function find($id)
    {
        return Expense::find($id);
    }

    /**
     * Создать новый расход
     *
     * @param array $data
     * @return Expense
     */
    public function create(array $data)
    {
        return Expense::create($data);
    }

    /**
     * Обновить существующий расход
     *
     * @param Expense $expense
     * @param array $data
     * @return Expense
     */
    public function update(Expense $expense, array $data)
    {
        $expense->update($data);
        return $expense;
    }

    /**
     * Удалить расход
     *
     * @param Expense $expense
     * @return bool
     */
    public function delete(Expense $expense)
    {
        return $expense->delete();
    }
}
