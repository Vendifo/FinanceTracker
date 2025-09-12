<?php

namespace App\Interfaces\Repositories;

use App\Models\Income;

interface IncomeRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Income $income, array $data);
    public function delete(Income $income);
}
