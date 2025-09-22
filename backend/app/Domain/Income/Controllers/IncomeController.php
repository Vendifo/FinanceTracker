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
    protected IncomeServiceInterface $incomeService;

    public function __construct(IncomeServiceInterface $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    public function index()
    {
        return response()->json($this->incomeService->all());
    }

    public function show($id)
    {
        $income = $this->incomeService->find($id);
        if (!$income) {
            return response()->json(['message' => 'Income not found']);
        }

        return response()->json($income, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:200',
            'amount' => 'required|numeric',
            'article_id' => 'required|exists:articles,id',
            'office_id' => 'required|exists:offices,id',
            'created_at' => 'nullable|date',
        ]);

        $income = $this->incomeService->create($data);

        return response()->json($income, 201);
    }


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

    public function destroy($id)
    {
        $income = $this->incomeService->delete($id);
        if (!$income) {
            return response()->json(['message' => 'Income not found']);
        }
        return response()->json(['message' => 'Income deleted']);
    }

    /**
     * Расширенный поиск доходов.
     *
     * Пример запроса:
     * GET /api/incomes/search?description=Аренда&amount_min=100&amount_max=1000&date_from=2024-01-01&date_to=2024-12-31
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $filters = $request->only([
            'description',
            'amount_min',
            'amount_max',
            'date_from',
            'date_to',
            'user_id',
            'article_id',
            'office_id',
        ]);

        $results = $this->incomeService->search($filters);

        return response()->json($results);
    }
}
