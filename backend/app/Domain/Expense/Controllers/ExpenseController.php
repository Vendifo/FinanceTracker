<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ExpenseServiceInterface;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseServiceInterface $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Список всех расходов
     */
    public function index()
    {
        return response()->json($this->expenseService->all());
    }

    /**
     * Просмотр конкретного расхода
     */
    public function show($id)
    {
        $expense = $this->expenseService->find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
        return response()->json($expense, 200);
    }

    /**
     * Создание нового расхода
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:200',
            'amount'      => 'required|numeric',
            'user_id'     => 'required|exists:users,id',
            'article_id'  => 'required|exists:articles,id',
            'office_id'   => 'required|exists:offices,id',
            'created_at' => 'sometimes|date',

        ]);

        $expense = $this->expenseService->create($request->only([
            'description',
            'amount',
            'user_id',
            'article_id',
            'office_id',
            'created_at',
        ]));

        return response()->json($expense, 201);
    }

    /**
     * Обновление существующего расхода
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'sometimes|string|max:200',
            'amount'      => 'sometimes|numeric',
            'user_id'     => 'sometimes|exists:users,id',
            'article_id'  => 'sometimes|exists:articles,id',
            'office_id'   => 'sometimes|exists:offices,id',
            'created_at' => 'sometimes|date',

        ]);

        $expense = $this->expenseService->update($id, $request->only([
            'description',
            'amount',
            'user_id',
            'article_id',
            'office_id',
            'created_at',
        ]));

        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json($expense, 200);
    }

    /**
     * Удаление расхода
     */
    public function destroy($id)
    {
        $deleted = $this->expenseService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return response()->json(['message' => 'Expense deleted'], 200);
    }
}
