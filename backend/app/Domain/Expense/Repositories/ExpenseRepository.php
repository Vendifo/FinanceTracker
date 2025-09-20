<?php

namespace App\Domain\Expense\Repositories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Репозиторий для работы с моделью расходов
 */
class ExpenseRepository implements ExpenseRepositoryInterface
{
    /** @inheritDoc */
    public function all()
    {
        return Expense::all();
    }

    /** @inheritDoc */
    public function find(int $id)
    {
        return Expense::find($id);
    }

    /** @inheritDoc */
    public function create(array $data)
    {
        return Expense::create($data);
    }

    /** @inheritDoc */
    public function update(Expense $expense, array $data)
    {
        $expense->update($data);
        return $expense;
    }

    /** @inheritDoc */
    public function delete(Expense $expense)
    {
        return $expense->delete();
    }

    /**
     * Расширенный поиск расходов по различным параметрам
     *
     * @param array $filters
     * @return Collection|Expense[]
     */
    public function search(array $filters): Collection
    {
        $query = Expense::query();

        // Поиск по описанию
        if (!empty($filters['description'])) {
            $desc = $filters['description'];

            // Сначала точное совпадение
            $query->where(function (Builder $q) use ($desc) {
                $q->where('description', $desc)
                    ->orWhere('description', 'LIKE', "%{$desc}%");
            });
        }

        // По сумме
        if (!empty($filters['amount_min'])) {
            $query->where('amount', '>=', $filters['amount_min']);
        }
        if (!empty($filters['amount_max'])) {
            $query->where('amount', '<=', $filters['amount_max']);
        }

        // По пользователю
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // По статье
        if (!empty($filters['article_id'])) {
            $query->where('article_id', $filters['article_id']);
        }

        // По офису
        if (!empty($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }

        // По дате
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Сортировка: сначала точные совпадения по description
        if (!empty($filters['description'])) {
            $query->orderByRaw("CASE WHEN description = ? THEN 0 ELSE 1 END", [$filters['description']]);
        }

        return $query->get();
    }
}
