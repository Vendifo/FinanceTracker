<?php

namespace App\Domain\Income\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс сервиса работы с доходами.
 * Определяет методы для CRUD операций и поиска.
 */
interface IncomeServiceInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    /**
     * Расширенный поиск доходов.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(array $filters): LengthAwarePaginator;
}
