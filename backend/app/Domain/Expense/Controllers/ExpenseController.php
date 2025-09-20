<?php

namespace App\Domain\Expense\Controllers;

use App\Core\BaseController;
use App\Domain\Expense\Requests\ExpenseRequest;
use App\Domain\Expense\Resources\ExpenseResource;
use App\Domain\Expense\Services\ExpenseServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Контроллер для управления расходами
 */
class ExpenseController extends BaseController
{
    protected ExpenseServiceInterface $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Получить список всех расходов
     */
    public function index()
    {
        $expenses = $this->expenseService->all();
        return ExpenseResource::collection($expenses);
    }

    /**
     * Поиск расходов по фильтрам
     */
    public function search(Request $request)
    {
        $filters = $request->only([
            'description',
            'amount_min',
            'amount_max',
            'user_id',
            'article_id',
            'office_id',
            'date_from',
            'date_to',
        ]);

        $expenses = $this->expenseService->search($filters);
        return ExpenseResource::collection($expenses);
    }

    /**
     * Получить конкретный расход по ID
     */
    public function show(int $id)
    {
        $expense = $this->expenseService->find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return new ExpenseResource($expense);
    }

    /**
     * Создать новый расход
     */
    public function store(ExpenseRequest $request)
    {
        $expense = $this->expenseService->create($request->validated());
        return new ExpenseResource($expense);
    }

    /**
     * Обновить существующий расход
     */
    public function update(ExpenseRequest $request, int $id)
    {
        $expense = $this->expenseService->update($id, $request->validated());

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return new ExpenseResource($expense);
    }

    /**
     * Удалить расход по ID
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->expenseService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json(['message' => 'Expense deleted'], 200);
    }
}
