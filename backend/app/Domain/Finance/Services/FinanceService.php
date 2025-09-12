<?php

namespace App\Domain\Finance\Services;

use App\Domain\Finance\Repositories\FinanceRepositoryInterface;

class FinanceService implements FinanceServiceInterface
{
    public function __construct(protected FinanceRepositoryInterface $repository) {}

    public function totalIncome(array $filters = []): float
    {
        return $this->repository->totalIncome($filters);
    }

    public function totalExpense(array $filters = []): float
    {
        return $this->repository->totalExpense($filters);
    }

    public function balance(array $filters = []): float
    {
        return $this->repository->balance($filters);
    }

    public function incomes(array $filters = [])
    {
        return $this->repository->incomes($filters);
    }

    public function expenses(array $filters = [])
    {
        return $this->repository->expenses($filters);
    }

    public function balanceByPeriod(array $filters = [])
    {
        return $this->repository->balanceByPeriod($filters);
    }

    public function byOffice(array $filters = [])
    {
        return $this->repository->byOffice($filters);
    }

    public function byArticle(array $filters = [])
    {
        return $this->repository->byArticle($filters);
    }
}
