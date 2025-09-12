<?php

namespace App\Domain\Income\Services;

/**
 * Интерфейс сервиса работы с доходами.
 * Определяет методы для CRUD операций.
 */
interface IncomeServiceInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
