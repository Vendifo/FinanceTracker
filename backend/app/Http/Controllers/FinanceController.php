<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FinanceService;
use App\Models\Income;
use App\Models\Expense;

class FinanceController extends Controller
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

}
