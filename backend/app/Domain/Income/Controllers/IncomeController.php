<?php

namespace App\Domain\Income\Controllers;

use App\Domain\Income\Services\IncomeServiceInterface;
use Illuminate\Http\Request;
use App\Core\BaseController;

/**
 * Контроллер для работы с доходами (Income).
 * Обрабатывает HTTP-запросы и делегирует логику сервису.
 */
class IncomeController extends BaseController
{
    /** @var IncomeServiceInterface Сервис для работы с доходами */
    protected IncomeServiceInterface $incomeService;

    /**
     * Внедрение зависимости сервиса через конструктор.
     *
     * @param IncomeServiceInterface $incomeService
     */
    public function __construct(IncomeServiceInterface $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    /**
     * Получить список всех доходов.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->incomeService->all());
    }

    /**
     * Показать конкретный доход по ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $income = $this->incomeService->find($id);
        if (!$income) {
            return response()->json(['message' => 'Income not found']);
        }

        return response()->json($income, 200);
    }

    /**
     * Создать новый доход.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:200',
            'amount' => 'required|numeric',
            'article_id' => 'required|exists:articles,id',
            'office_id' => 'required|exists:offices,id',
            'created_at' => 'nullable|date', // если не указано, будет текущее время
        ]);

        $income = $this->incomeService->create($data);

        return response()->json($income, 201);
    }

    /**
     * Обновить существующий доход.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'sometimes|string|max:200',
            'amount' => 'sometimes|numeric',
            'article_id' => 'sometimes|exists:articles,id',
            'office_id' => 'sometimes|exists:offices,id',
            'created_at' => 'sometimes|date',
        ]);

        $income = $this->incomeService->update($id, $data);

        if (!$income) {
            return response()->json(['message' => 'Income not found'], 404);
        }

        return response()->json($income, 200);
    }

    /**
     * Удалить доход.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $income = $this->incomeService->delete($id);
        if (!$income) {
            return response()->json(['message' => 'Income not found']);
        }
        return response()->json(['message' => 'Income deleted']);
    }
}
