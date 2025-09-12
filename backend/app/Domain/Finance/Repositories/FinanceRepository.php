<?php

namespace App\Domain\Finance\Repositories;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Office;
use App\Models\Article;

class FinanceRepository implements FinanceRepositoryInterface
{
    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['office_id'])) $query->where('office_id', $filters['office_id']);
        if (!empty($filters['article_id'])) $query->where('article_id', $filters['article_id']);
        if (!empty($filters['user_id'])) $query->where('user_id', $filters['user_id']);
        if (!empty($filters['from'])) $query->whereDate('created_at', '>=', $filters['from']);
        if (!empty($filters['to'])) $query->whereDate('created_at', '<=', $filters['to']);
        if (!empty($filters['date'])) $query->whereDate('created_at', $filters['date']);
        if (!empty($filters['year'])) $query->whereYear('created_at', $filters['year']);
        if (!empty($filters['month'])) $query->whereMonth('created_at', $filters['month']);
        if (!empty($filters['day'])) $query->whereDay('created_at', $filters['day']);
        return $query;
    }

    public function totalIncome(array $filters = []): float
    {
        return (float) $this->applyFilters(Income::query(), $filters)->sum('amount');
    }

    public function totalExpense(array $filters = []): float
    {
        return (float) $this->applyFilters(Expense::query(), $filters)->sum('amount');
    }

    public function balance(array $filters = []): float
    {
        return $this->totalIncome($filters) - $this->totalExpense($filters);
    }

    public function incomes(array $filters = [])
    {
        return $this->applyFilters(Income::query(), $filters)->get();
    }

    public function expenses(array $filters = [])
    {
        return $this->applyFilters(Expense::query(), $filters)->get();
    }

    public function balanceByPeriod(array $filters = []): array
    {
        return [
            'income'  => $this->totalIncome($filters),
            'expense' => $this->totalExpense($filters),
            'balance' => $this->balance($filters),
        ];
    }

    public function byOffice(array $filters = [])
    {
        $from = $filters['from'] ?? '1900-01-01';
        $to   = $filters['to'] ?? now()->toDateString();
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

    public function byArticle(array $filters = [])
    {
        $from = $filters['from'] ?? '1900-01-01';
        $to   = $filters['to'] ?? now()->toDateString();
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

        $articles = Article::all();

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
