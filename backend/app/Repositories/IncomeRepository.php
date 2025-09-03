<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IncomeRepositoryInterface;
use App\Models\Income;

class IncomeRepository implements IncomeRepositoryInterface
{
    public function all()
    {
        return Income::all();
    }

    public function find($id)
    {
        return Income::find($id);
    }

    public function create(array $data)
    {
        return Income::create($data);
    }

    public function update(Income $income, array $data)
    {
        $income->update($data);
        return $income;
    }

    public function delete(Income $income)
    {
        return $income->delete();
    }

    public function getByOffice($id)
    {
        return Income::where('office_id', $id)->get();
    }
}
