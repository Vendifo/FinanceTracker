<?php

namespace App\Interfaces\Repositories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

/**
 * Интерфейс для работы с репозиторием расходов
 *
 * @package App\Interfaces\Repositories
 */
interface ExpenseRepositoryInterface
{
    /**
     * Получить все расходы
     *
     * @return Collection|Expense[]
     */
    public function all();

    /**
     * Найти расход по ID
     *
     * @param int $id
     * @return Expense|null
     */
    public function find($id);

    /**
     * Создать новый расход
     *
     * @param array $data
     * @return Expense
     */
    public function create(array $data);

    /**
     * Обновить существующий расход
     *
     * @param Expense $expense
     * @param array $data
     * @return Expense
     */
    public function update(Expense $expense, array $data);

    /**
     * Удалить расход
     *
     * @param Expense $expense
     * @return bool
     */
    public function delete(Expense $expense);
}
