<?php

namespace App\Domain\Finance\Services;

use App\Domain\Finance\Repositories\FinanceRepositoryInterface;

/**
 * Сервис для работы с финансовыми данными.
 *
 * Абстрагирует логику работы с доходами, расходами и балансами,
 * используя репозиторий для получения данных из базы.
 */
class FinanceService implements FinanceServiceInterface
{
    /**
     * @param FinanceRepositoryInterface $repository
     * Репозиторий для доступа к данным доходов и расходов.
     */
    public function __construct(protected FinanceRepositoryInterface $repository) {}

    /**
     * Получить общую сумму доходов по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Общая сумма доходов
     */
    public function totalIncome(array $filters = []): float
    {
        return $this->repository->totalIncome($filters);
    }

    /**
     * Получить общую сумму расходов по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Общая сумма расходов
     */
    public function totalExpense(array $filters = []): float
    {
        return $this->repository->totalExpense($filters);
    }

    /**
     * Получить баланс (доходы минус расходы) по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Баланс
     */
    public function balance(array $filters = []): float
    {
        return $this->repository->balance($filters);
    }

    /**
     * Получить список всех доходов по фильтрам.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function incomes(array $filters = [])
    {
        return $this->repository->incomes($filters);
    }

    /**
     * Получить список всех расходов по фильтрам.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function expenses(array $filters = [])
    {
        return $this->repository->expenses($filters);
    }

    /**
     * Получить финансовый баланс за период.
     *
     * @param array $filters Фильтры по дате и другим параметрам
     * @return array ['income' => float, 'expense' => float, 'balance' => float]
     */
    public function balanceByPeriod(array $filters = [])
    {
        return $this->repository->balanceByPeriod($filters);
    }

    /**
     * Получить финансовые данные по офисам.
     *
     * @param array $filters Фильтры: офис, период и т.д.
     * @return \Illuminate\Support\Collection
     */
    public function byOffice(array $filters = [])
    {
        return $this->repository->byOffice($filters);
    }

    /**
     * Получить финансовые данные по статьям.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return \Illuminate\Support\Collection
     */
    public function byArticle(array $filters = [])
    {
        return $this->repository->byArticle($filters);
    }
}
