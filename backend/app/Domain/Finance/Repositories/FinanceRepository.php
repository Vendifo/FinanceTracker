<?php

namespace App\Domain\Finance\Repositories;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Office;
use App\Models\Article;

class FinanceRepository implements FinanceRepositoryInterface
{
    /**
     * Применяет фильтры к запросу Eloquent
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters Массив фильтров (office_id, article_id, user_id, date, from, to, year, month, day)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['office_id'])) $query->where('office_id', $filters['office_id']);
        if (!empty($filters['article_id'])) $query->where('article_id', $filters['article_id']);
        if (!empty($filters['user_id'])) $query->where('user_id', $filters['user_id']);

        if (!empty($filters['from']) || !empty($filters['to'])) {
            if (!empty($filters['from'])) $query->whereDate('created_at', '>=', $filters['from']);
            if (!empty($filters['to'])) $query->whereDate('created_at', '<=', $filters['to']);
        } elseif (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        if (!empty($filters['year'])) $query->whereYear('created_at', $filters['year']);
        if (!empty($filters['month'])) $query->whereMonth('created_at', $filters['month']);
        if (!empty($filters['day'])) $query->whereDay('created_at', $filters['day']);

        return $query;
    }


    /**
     * Возвращает общую сумму доходов по фильтрам
     *
     * @param array $filters
     * @return float
     */
    public function totalIncome(array $filters = []): float
    {
        return (float) $this->applyFilters(Income::query(), $filters)->sum('amount');
    }

    /**
     * Возвращает общую сумму расходов по фильтрам
     *
     * @param array $filters
     * @return float
     */
    public function totalExpense(array $filters = []): float
    {
        return (float) $this->applyFilters(Expense::query(), $filters)->sum('amount');
    }

    /**
     * Возвращает баланс (доходы минус расходы) по фильтрам
     *
     * @param array $filters
     * @return float
     */
    public function balance(array $filters = []): float
    {
        return $this->totalIncome($filters) - $this->totalExpense($filters);
    }

    /**
     * Возвращает коллекцию доходов с применением фильтров
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function incomes(array $filters = [])
    {
        return $this->applyFilters(Income::query(), $filters)->get();
    }

    /**
     * Возвращает коллекцию расходов с применением фильтров
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function expenses(array $filters = [])
    {
        return $this->applyFilters(Expense::query(), $filters)->get();
    }

    /**
     * Возвращает баланс за период: доходы, расходы и итоговый баланс
     *
     * @param array $filters
     * @return array
     */
    public function balanceByPeriod(array $filters = []): array
    {
        return [
            'income'  => $this->totalIncome($filters),
            'expense' => $this->totalExpense($filters),
            'balance' => $this->balance($filters),
        ];
    }

    /**
     * Возвращает финансовые показатели по офисам
     *
     * @param array $filters Фильтры (office_id, date_from, date_to)
     * @return \Illuminate\Support\Collection
     */
    public function byOffice(array $filters = [])
    {
        $from = $filters['from'] ?? $filters['date_from'] ?? '1900-01-01';
        $to   = $filters['to'] ?? $filters['date_to'] ?? now()->toDateString();

        // Приводим даты к диапазону дня
        $from = date('Y-m-d 00:00:00', strtotime($from));
        $to   = date('Y-m-d 23:59:59', strtotime($to));

        $officeId = $filters['office_id'] ?? null;

        $incomes = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('office_id, SUM(amount) as total_income')
            ->groupBy('office_id')
            ->pluck('total_income', 'office_id');

        $expenses = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('office_id, SUM(amount) as total_expense')
            ->groupBy('office_id')
            ->pluck('total_expense', 'office_id');

        $offices = Office::query()
            ->when($officeId, fn($q) => $q->where('id', $officeId))
            ->get();

        return $offices->map(function ($office) use ($incomes, $expenses) {
            $income = $incomes[$office->id] ?? 0;
            $expense = $expenses[$office->id] ?? 0;

            return [
                'office_id' => $office->id,
                'office_name' => $office->name,
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        });
    }

    /**
     * Возвращает финансовые показатели по статьям
     *
     * @param array $filters Фильтры (office_id, date_from, date_to)
     * @return \Illuminate\Support\Collection
     */
    public function byArticle(array $filters = [])
    {
        $from = $filters['from'] ?? $filters['date_from'] ?? '1900-01-01';
        $to   = $filters['to'] ?? $filters['date_to'] ?? now()->toDateString();

        // Приводим даты к диапазону дня
        $from = date('Y-m-d 00:00:00', strtotime($from));
        $to   = date('Y-m-d 23:59:59', strtotime($to));

        $officeId = $filters['office_id'] ?? null;

        $incomes = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('article_id, SUM(amount) as total_income')
            ->groupBy('article_id')
            ->pluck('total_income', 'article_id');

        $expenses = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('article_id, SUM(amount) as total_expense')
            ->groupBy('article_id')
            ->pluck('total_expense', 'article_id');

        $articleIds = array_unique(array_merge($incomes->keys()->toArray(), $expenses->keys()->toArray()));
        $articles = Article::whereIn('id', $articleIds)->get();

        return $articles->map(function ($article) use ($incomes, $expenses) {
            $income = $incomes[$article->id] ?? 0;
            $expense = $expenses[$article->id] ?? 0;

            return [
                'article_id' => $article->id,
                'article_name' => $article->name,
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        });
    }
}
