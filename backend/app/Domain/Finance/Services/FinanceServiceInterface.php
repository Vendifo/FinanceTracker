<?php

namespace App\Domain\Finance\Services;

interface FinanceServiceInterface
{
    public function totalIncome(array $filters = []): float;
    public function totalExpense(array $filters = []): float;
    public function balance(array $filters = []): float;
    public function incomes(array $filters = []);
    public function expenses(array $filters = []);
    public function balanceByPeriod(array $filters = []);
    public function byOffice(array $filters = []);
    public function byArticle(array $filters = []);
}
