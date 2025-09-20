<?php

namespace App\Domain\Income\Repositories;

use App\Models\Income;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс репозитория для работы с доходами.
 * Определяет базовые CRUD методы и расширенный поиск.
 */
interface IncomeRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Income $income, array $data);
    public function delete(Income $income);

    /**
     * Расширенный поиск доходов по фильтрам.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(array $filters): LengthAwarePaginator;
}
