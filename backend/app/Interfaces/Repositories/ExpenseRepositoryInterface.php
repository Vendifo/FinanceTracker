<?php

namespace App\Interfaces\Repositories;

use App\Models\Expense;

interface ExpenseRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Expense $expense, array $data);
    public function delete(Expense $expense);
}
