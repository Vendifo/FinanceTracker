<?php

namespace App\Services;

use App\Interfaces\Services\IncomeServiceInterface;
use App\Repositories\IncomeRepository;

class IncomeService implements IncomeServiceInterface
{
    protected IncomeRepository $incomeRepository;

    public function __construct(IncomeRepository $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }

    public function all()
    {
        return $this->incomeRepository->all();
    }

    public function find($id)
    {
        return $this->incomeRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->incomeRepository->create($data);
    }

    public function update($id, array $data)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return null;
        }

        return $this->incomeRepository->update($income, $data);
    }

    public function delete($id)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return false;
        }

        return $this->incomeRepository->delete($income);
    }

    public function getByOffice($id)
    {
        return $this->incomeRepository->getByOffice($id);
    }
}
