<?php

namespace App\Interfaces\Services;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

/**
 * Интерфейс сервиса для работы с расходами
 *
 * @package App\Interfaces\Services
 */
interface ExpenseServiceInterface
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
     * @param int $id
     * @param array $data
     * @return Expense|null
     */
    public function update($id, array $data);

    /**
     * Удалить расход
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);
}
