<?php

namespace App\Services;

use App\Interfaces\Repositories\ExpenseRepositoryInterface;
use App\Interfaces\Services\ExpenseServiceInterface;
use App\Repositories\ExpenseRepository;

class ExpenseService implements ExpenseServiceInterface
{
    protected ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function all()
    {
        return $this->expenseRepository->all();
    }

    public function find($id)
    {
        return $this->expenseRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->expenseRepository->create($data);

    }

    public function update($id, array $data)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense)
        {
            return null;
        }

        return $this->expenseRepository->update($expense, $data);
    }

    public function delete($id)
    {
        $expense = $this->expenseRepository->find($id);
        if (!$expense)
        {
            return false;
        }

        return $this->expenseRepository->delete($expense);
    }
}
