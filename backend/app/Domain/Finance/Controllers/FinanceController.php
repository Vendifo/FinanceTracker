<?php

namespace App\Domain\Finance\Controllers;

use App\Core\BaseController;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Finance\Requests\FinanceRequest;
use App\Domain\Finance\Resources\FinanceSummaryResource;

class FinanceController extends BaseController
{
    public function __construct(protected FinanceService $service) {}

    public function summary(FinanceRequest $request)
    {
        $filters = $request->validated();
        $result = [
            'filters' => array_filter($filters),
            'income' => $this->service->totalIncome($filters),
            'expense' => $this->service->totalExpense($filters),
            'balance' => $this->service->balance($filters),
        ];

        return new FinanceSummaryResource($result);
    }

    public function index(FinanceRequest $request)
    {
        $filters = $request->validated();
        $date = $filters['date'] ?? now()->toDateString();
        $filters['from'] = $date;
        $filters['to'] = $date;

        return response()->json([
            'incomes' => $this->service->incomes($filters),
            'expenses' => $this->service->expenses($filters),
            'balance' => $this->service->balance($filters),
        ]);
    }

    public function dynamics(FinanceRequest $request)
    {
        $filters = $request->validated();
        $filters['from'] = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $filters['to'] = $filters['date_to'] ?? now()->toDateString();

        return response()->json([
            'filters' => $filters,
            'incomes' => $this->service->incomes($filters),
            'expenses' => $this->service->expenses($filters),
        ]);
    }

    public function balancePeriod(FinanceRequest $request)
    {
        $filters = $request->validated();
        $filters['from'] = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $filters['to'] = $filters['date_to'] ?? now()->toDateString();

        return response()->json([
            'filters' => $filters,
        ] + $this->service->balanceByPeriod($filters));
    }

    public function byOffice(FinanceRequest $request)
    {
        $filters = $request->validated();
        $filters['from'] = $filters['date_from'] ?? '1900-01-01';
        $filters['to'] = $filters['date_to'] ?? now()->toDateString();

        return response()->json([
            'filters' => $filters,
            'offices' => $this->service->byOffice($filters),
        ]);
    }

    public function byArticle(FinanceRequest $request)
    {
        $filters = $request->validated();
        $filters['from'] = $filters['date_from'] ?? '1900-01-01';
        $filters['to'] = $filters['date_to'] ?? now()->toDateString();

        return response()->json([
            'filters' => $filters,
            'articles' => $this->service->byArticle($filters),
        ]);
    }
}
