<?php

namespace App\Domain\Finance\Repositories;

/**
 * Интерфейс репозитория финансовых данных.
 *
 * Определяет методы для работы с доходами, расходами, балансом,
 * а также агрегированными данными по офисам и статьям.
 */
interface FinanceRepositoryInterface
{
    /**
     * Возвращает общую сумму доходов по фильтрам.
     *
     * @param array $filters Фильтры (office_id, article_id, user_id, date, from, to, year, month, day)
     * @return float
     */
    public function totalIncome(array $filters = []): float;

    /**
     * Возвращает общую сумму расходов по фильтрам.
     *
     * @param array $filters Фильтры (office_id, article_id, user_id, date, from, to, year, month, day)
     * @return float
     */
    public function totalExpense(array $filters = []): float;

    /**
     * Возвращает баланс (доходы минус расходы) по фильтрам.
     *
     * @param array $filters
     * @return float
     */
    public function balance(array $filters = []): float;

    /**
     * Возвращает коллекцию доходов с применением фильтров.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function incomes(array $filters = []);

    /**
     * Возвращает коллекцию расходов с применением фильтров.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function expenses(array $filters = []);

    /**
     * Возвращает баланс за период: доходы, расходы и итоговый баланс.
     *
     * @param array $filters
     * @return array ['income' => float, 'expense' => float, 'balance' => float]
     */
    public function balanceByPeriod(array $filters = []): array;

    /**
     * Возвращает финансовые показатели по офисам.
     *
     * @param array $filters Фильтры (office_id, date_from, date_to)
     * @return \Illuminate\Support\Collection
     */
    public function byOffice(array $filters = []);

    /**
     * Возвращает финансовые показатели по статьям.
     *
     * @param array $filters Фильтры (office_id, date_from, date_to)
     * @return \Illuminate\Support\Collection
     */
    public function byArticle(array $filters = []);
}
