<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Expense;

class FinanceService
{
    /**
     * Получить общую сумму доходов с фильтрами
     *
     * @param array $filters
     * @return float
     */
    public function totalIncome(array $filters = []): float
    {
        $query = Income::query();
        $query = $this->applyFilters($query, $filters);
        return (float) $query->sum('amount');
    }

    /**
     * Получить общую сумму расходов с фильтрами
     *
     * @param array $filters
     * @return float
     */
    public function totalExpense(array $filters = []): float
    {
        $query = Expense::query();
        $query = $this->applyFilters($query, $filters);
        return (float) $query->sum('amount');
    }

    /**
     * Получить баланс: доходы минус расходы
     *
     * @param array $filters
     * @return float
     */
    public function balance(array $filters = []): float
    {
        return $this->totalIncome($filters) - $this->totalExpense($filters);
    }

    /**
     * Применить фильтры к запросу (офис, статья, дата, день, месяц, год)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }

        if (!empty($filters['article_id'])) {
            $query->where('article_id', $filters['article_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        } else {
            if (!empty($filters['year'])) {
                $query->whereYear('created_at', $filters['year']);
            }
            if (!empty($filters['month'])) {
                $query->whereMonth('created_at', $filters['month']);
            }
            if (!empty($filters['day'])) {
                $query->whereDay('created_at', $filters['day']);
            }
        }

        return $query;
    }
}
