<?php

namespace App\Domain\Income\Services;

use App\Domain\Income\Repositories\IncomeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Сервис для работы с доходами.
 * Инкапсулирует бизнес-логику, делегируя работу репозиторию.
 */
class IncomeService implements IncomeServiceInterface
{
    protected IncomeRepositoryInterface $incomeRepository;

    public function __construct(IncomeRepositoryInterface $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }

    public function all()
    {
        return $this->incomeRepository->all();
    }

    public function find($id)
    {
        return $this->incomeRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->incomeRepository->create($data);
    }

    public function update($id, array $data)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return null;
        }

        return $this->incomeRepository->update($income, $data);
    }

    public function delete($id)
    {
        $income = $this->incomeRepository->find($id);
        if (!$income) {
            return false;
        }

        return $this->incomeRepository->delete($income);
    }

    /**
     * Расширенный поиск доходов.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(array $filters): LengthAwarePaginator
    {
        return $this->incomeRepository->search($filters);
    }
}
