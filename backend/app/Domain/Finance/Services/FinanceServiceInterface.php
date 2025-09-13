<?php

namespace App\Domain\Finance\Services;

/**
 * Интерфейс сервиса работы с финансовыми данными.
 *
 * Определяет методы для получения доходов, расходов, балансов,
 * а также агрегации по офисам и статьям.
 */
interface FinanceServiceInterface
{
    /**
     * Получить общую сумму доходов по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Общая сумма доходов
     */
    public function totalIncome(array $filters = []): float;

    /**
     * Получить общую сумму расходов по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Общая сумма расходов
     */
    public function totalExpense(array $filters = []): float;

    /**
     * Получить баланс (доходы минус расходы) по заданным фильтрам.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return float Баланс
     */
    public function balance(array $filters = []): float;

    /**
     * Получить список доходов по фильтрам.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function incomes(array $filters = []);

    /**
     * Получить список расходов по фильтрам.
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function expenses(array $filters = []);

    /**
     * Получить финансовый баланс за период.
     *
     * @param array $filters Фильтры по дате и другим параметрам
     * @return array ['income' => float, 'expense' => float, 'balance' => float]
     */
    public function balanceByPeriod(array $filters = []);

    /**
     * Получить финансовые данные по офисам.
     *
     * @param array $filters Фильтры: офис, период и т.д.
     * @return \Illuminate\Support\Collection
     */
    public function byOffice(array $filters = []);

    /**
     * Получить финансовые данные по статьям.
     *
     * @param array $filters Фильтры: офис, статья, период и т.д.
     * @return \Illuminate\Support\Collection
     */
    public function byArticle(array $filters = []);
}
