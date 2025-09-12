<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ExpenseRepositoryInterface;
use App\Models\Expense;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function all()
    {
        return Expense::all();
    }

    public function find($id)
    {
        return Expense::find($id);
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update(Expense $expense, array $data)
    {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense)
    {
        return $expense->delete();
    }
}
