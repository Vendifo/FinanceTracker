<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IncomeServiceInterface;
use Illuminate\Http\Request;

class IncomeController extends Controller
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
        'created_at' => 'nullable|date', // можно не указывать, тогда будет текущее время
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


    public function byOffice($id)
    {
        $income = $this->incomeService->getByOffice($id);
        return response()->json(["data" => $income]);
    }
}
