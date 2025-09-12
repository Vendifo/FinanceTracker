<?php

namespace App\Domain\Finance\Controllers;

use App\Core\BaseController;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Finance\Requests\FinanceRequest;
use App\Domain\Finance\Resources\FinanceSummaryResource;

class FinanceController extends BaseController
{
    /**
     * Конструктор контроллера FinanceController
     *
     * @param FinanceService $service Сервис для работы с финансовыми данными
     */
    public function __construct(protected FinanceService $service) {}

    /**
     * Возвращает финансовую сводку (summary) по фильтрам
     *
     * @param FinanceRequest $request Запрос с фильтрами (office_id, article_id, date_from, date_to)
     * @return FinanceSummaryResource
     */
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

    /**
     * Возвращает список доходов и расходов за определенный день
     *
     * @param FinanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Возвращает динамику доходов и расходов за период
     *
     * @param FinanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Возвращает баланс за период
     *
     * @param FinanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balancePeriod(FinanceRequest $request)
    {
        $filters = $request->validated();
        $filters['from'] = $filters['date_from'] ?? now()->startOfMonth()->toDateString();
        $filters['to'] = $filters['date_to'] ?? now()->toDateString();

        return response()->json([
            'filters' => $filters,
        ] + $this->service->balanceByPeriod($filters));
    }

    /**
     * Возвращает финансовые показатели по офисам
     *
     * @param FinanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Возвращает финансовые показатели по статьям
     *
     * @param FinanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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
