<?php

namespace App\Domain\Expense\Controllers;

use App\Domain\Expense\Services\ExpenseServiceInterface;
use App\Domain\Expense\Requests\ExpenseRequest;
use App\Domain\Expense\Resources\ExpenseResource;
use Illuminate\Http\JsonResponse;

use App\Core\BaseController;

/**
 * Контроллер для управления расходами
 *
 * @package App\Http\Controllers
 */
class ExpenseController extends BaseController
{
    /**
     * @var ExpenseServiceInterface Сервис для работы с расходами
     */
    protected ExpenseServiceInterface $expenseService;

    /**
     * ExpenseController constructor.
     *
     * @param ExpenseServiceInterface $expenseService
     */
    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Получить список всех расходов.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $expenses = $this->expenseService->all();
        return ExpenseResource::collection($expenses);
    }

    /**
     * Получить конкретный расход по ID.
     *
     * @param int $id
     * @return ExpenseResource|JsonResponse
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
     * Создать новый расход.
     *
     * @param ExpenseRequest $request
     * @return ExpenseResource
     */
    public function store(ExpenseRequest $request)
    {
        $expense = $this->expenseService->create($request->validated());
        return new ExpenseResource($expense);
    }

    /**
     * Обновить существующий расход.
     *
     * @param ExpenseRequest $request
     * @param int $id
     * @return ExpenseResource|JsonResponse
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
     * Удалить расход по ID.
     *
     * @param int $id
     * @return JsonResponse
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
