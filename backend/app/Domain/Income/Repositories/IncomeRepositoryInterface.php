<?php

namespace App\Domain\Income\Repositories;

use App\Models\Income;

/**
 * Интерфейс репозитория для работы с доходами.
 * Определяет базовые CRUD методы.
 */
interface IncomeRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Income $income, array $data);
    public function delete(Income $income);
}
