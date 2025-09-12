<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FinanceService;
use App\Models\Income;
use App\Models\Expense;

class FinanceController extends BaseController
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Получение сводки финансов: доходы, расходы, баланс
     *
     * Query-параметры (необязательные):
     * - office_id
     * - article_id
     * - year
     * - month
     * - day
     * - date (YYYY-MM-DD)
     */
    public function summary(Request $request)
    {
        // Берем все возможные фильтры
        $filters = $request->only(['office_id', 'article_id', 'year', 'month', 'day', 'date']);

        // Считаем доходы, расходы и баланс
        $income  = $this->financeService->totalIncome($filters);
        $expense = $this->financeService->totalExpense($filters);
        $balance = $this->financeService->balance($filters);

        return response()->json([
            'filters' => array_filter($filters), // только заполненные фильтры
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
        ]);
    }

    public function index(Request $request)
    {
        $officeId = $request->office_id;
        $date = $request->date ?? now()->toDateString();

        // Для таблиц: только выбранная дата
        $recordsDate = $date; // отображаем за этот день

        $incomesToday = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereDate('created_at', $recordsDate)
            ->get();

        $expensesToday = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereDate('created_at', $recordsDate)
            ->get();

        // Для баланса: до выбранной даты
        $totalIncome = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereDate('created_at', '<=', $recordsDate)
            ->sum('amount');

        $totalExpense = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->whereDate('created_at', '<=', $recordsDate)
            ->sum('amount');

        return response()->json([
            'incomes' => $incomesToday,
            'expenses' => $expensesToday,
            'balance' => $totalIncome - $totalExpense
        ]);
    }

    public function dynamics(Request $request)
    {
        $officeId = $request->office_id;
        $articleId = $request->article_id;
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        $incomes = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->when($articleId, fn($q) => $q->where('article_id', $articleId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $expenses = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->when($articleId, fn($q) => $q->where('article_id', $articleId))
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'filters' => [
                'office_id' => $officeId,
                'article_id' => $articleId,
                'date_from' => $from,
                'date_to' => $to,
            ],
            'incomes' => $incomes,
            'expenses' => $expenses,
        ]);
    }
    public function balancePeriod(Request $request)
    {
        $officeId = $request->office_id;
        $articleId = $request->article_id;
        $userId = $request->user_id;
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to = $request->date_to ?? now()->toDateString();

        $totalIncome = Income::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->when($articleId, fn($q) => $q->where('article_id', $articleId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        $totalExpense = Expense::query()
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->when($articleId, fn($q) => $q->where('article_id', $articleId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        return response()->json([
            'filters' => [
                'office_id' => $officeId,
                'article_id' => $articleId,
                'user_id' => $userId,
                'date_from' => $from,
                'date_to' => $to,
            ],
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
        ]);
    }

    public function byOffice(Request $request)
{
    $from = $request->date_from ?? '1900-01-01'; // с какой даты считать
    $to   = $request->date_to ?? now()->toDateString(); // по какую дату
    $officeId = $request->office_id;

    $queryIncomes = Income::query()
        ->when($officeId, fn($q) => $q->where('office_id', $officeId))
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('office_id, SUM(amount) as total_income')
        ->groupBy('office_id');

    $queryExpenses = Expense::query()
        ->when($officeId, fn($q) => $q->where('office_id', $officeId))
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('office_id, SUM(amount) as total_expense')
        ->groupBy('office_id');

    // Склеиваем доходы и расходы
    $incomes = $queryIncomes->pluck('total_income', 'office_id');
    $expenses = $queryExpenses->pluck('total_expense', 'office_id');

    // Получаем список всех офисов (чтобы не потерялись те, где только расходы или только доходы)
    $offices = \App\Models\Office::query()
        ->when($officeId, fn($q) => $q->where('id', $officeId))
        ->get();

    $result = $offices->map(function ($office) use ($incomes, $expenses) {
        $income = $incomes[$office->id] ?? 0;
        $expense = $expenses[$office->id] ?? 0;

        return [
            'office_id'   => $office->id,
            'office_name' => $office->name,
            'income'      => $income,
            'expense'     => $expense,
            'balance'     => $income - $expense,
        ];
    });

    return response()->json([
        'filters' => [
            'office_id' => $officeId,
            'date_from' => $from,
            'date_to'   => $to,
        ],
        'offices' => $result,
    ]);
}
public function byArticle(Request $request)
{
    $from = $request->date_from ?? '1900-01-01';
    $to   = $request->date_to ?? now()->toDateString();
    $officeId = $request->office_id;

    // Доходы по статьям
    $queryIncomes = Income::query()
        ->when($officeId, fn($q) => $q->where('office_id', $officeId))
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('article_id, SUM(amount) as total_income')
        ->groupBy('article_id');

    // Расходы по статьям
    $queryExpenses = Expense::query()
        ->when($officeId, fn($q) => $q->where('office_id', $officeId))
        ->whereBetween('created_at', [$from, $to])
        ->selectRaw('article_id, SUM(amount) as total_expense')
        ->groupBy('article_id');

    $incomes = $queryIncomes->pluck('total_income', 'article_id');
    $expenses = $queryExpenses->pluck('total_expense', 'article_id');

    // Получаем список статей (чтобы вернуть даже те, где только доход или только расход)
    $articles = \App\Models\Article::all();

    $result = $articles->map(function ($article) use ($incomes, $expenses) {
        $income = $incomes[$article->id] ?? 0;
        $expense = $expenses[$article->id] ?? 0;

        return [
            'article_id'   => $article->id,
            'article_name' => $article->name,
            'income'       => $income,
            'expense'      => $expense,
            'balance'      => $income - $expense,
        ];
    });

    return response()->json([
        'filters' => [
            'office_id' => $officeId,
            'date_from' => $from,
            'date_to'   => $to,
        ],
        'articles' => $result,
    ]);
}

}
