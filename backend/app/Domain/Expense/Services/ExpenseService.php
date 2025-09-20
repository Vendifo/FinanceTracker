<?php

namespace App\Domain\Expense\Services;

use App\Domain\Expense\Repositories\ExpenseRepositoryInterface;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

/**
 * Сервис для работы с расходами
 */
class ExpenseService implements ExpenseServiceInterface
{
    protected ExpenseRepositoryInterface $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /** @inheritDoc */
    public function all()
    {
        return $this->expenseRepository->all();
    }

    /** @inheritDoc */
    public function find(int $id)
    {
        return $this->expenseRepository->find($id);
    }

    /** @inheritDoc */
    public function create(array $data)
    {
        return $this->expenseRepository->create($data);
    }

    /** @inheritDoc */
    public function update(int $id, array $data)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense) {
            return null;
        }

        return $this->expenseRepository->update($expense, $data);
    }

    /** @inheritDoc */
    public function delete(int $id)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense) {
            return false;
        }

        return $this->expenseRepository->delete($expense);
    }

    /** @inheritDoc */
    public function search(array $filters)
    {
        return $this->expenseRepository->search($filters);
    }
}
