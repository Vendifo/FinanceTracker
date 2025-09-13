<?php

namespace App\Domain\Expense\Services;

use App\Domain\Expense\Repositories\ExpenseRepositoryInterface;
use App\Domain\Expense\Services\ExpenseServiceInterface;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

/**
 * Сервис для работы с расходами
 *
 * @package App\Services
 */
class ExpenseService implements ExpenseServiceInterface
{
    /**
     * @var ExpenseRepositoryInterface Репозиторий для работы с расходами
     */
    protected ExpenseRepositoryInterface $expenseRepository;

    /**
     * ExpenseService constructor.
     *
     * @param ExpenseRepositoryInterface $expenseRepository
     */
    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Получить все расходы
     *
     * @return Collection|Expense[]
     */
    public function all()
    {
        return $this->expenseRepository->all();
    }

    /**
     * Найти расход по ID
     *
     * @param int $id
     * @return Expense|null
     */
    public function find($id)
    {
        return $this->expenseRepository->find($id);
    }

    /**
     * Создать новый расход
     *
     * @param array $data
     * @return Expense
     */
    public function create(array $data)
    {
        return $this->expenseRepository->create($data);
    }

    /**
     * Обновить существующий расход
     *
     * @param int $id
     * @param array $data
     * @return Expense|null
     */
    public function update($id, array $data)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense) {
            return null;
        }

        return $this->expenseRepository->update($expense, $data);
    }

    /**
     * Удалить расход по ID
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense) {
            return false;
        }

        return $this->expenseRepository->delete($expense);
    }
}
