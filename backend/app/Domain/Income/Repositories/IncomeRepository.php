<?php

namespace App\Domain\Income\Repositories;

use App\Models\Income;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Репозиторий для работы с моделью Income.
 * Реализует базовые CRUD операции и расширенный поиск.
 */
class IncomeRepository implements IncomeRepositoryInterface
{
    public function all()
    {
        return Income::all();
    }

    public function find($id)
    {
        return Income::find($id);
    }

    public function create(array $data)
    {
        return Income::create($data);
    }

    public function update(Income $income, array $data)
    {
        $income->update($data);
        return $income;
    }

    public function delete(Income $income)
    {
        return $income->delete();
    }

    /**
     * Поиск доходов по фильтрам (описание, суммы, дата, связи).
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(array $filters): LengthAwarePaginator
    {
        $query = Income::query();

        if (!empty($filters['description'])) {
            $desc = $filters['description'];

            // Сначала точное совпадение, потом LIKE
            $query->where(function ($q) use ($desc) {
                $q->where('description', $desc)
                    ->orWhere('description', 'LIKE', "%{$desc}%");
            });

            // Сортировка: точные совпадения идут первыми
            $query->orderByRaw("CASE WHEN description = ? THEN 0 ELSE 1 END", [$desc]);
        }

        if (!empty($filters['amount_min'])) {
            $query->where('amount', '>=', $filters['amount_min']);
        }

        if (!empty($filters['amount_max'])) {
            $query->where('amount', '<=', $filters['amount_max']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['article_id'])) {
            $query->where('article_id', $filters['article_id']);
        }

        if (!empty($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }

        return $query->paginate(15);
    }
}
