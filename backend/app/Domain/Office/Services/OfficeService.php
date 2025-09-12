<?php

namespace App\Services;

use App\Interfaces\Services\OfficeServiceInterface;
use App\Repositories\OfficeRepository;
use App\Models\Office;

class OfficeService implements OfficeServiceInterface
{
    protected OfficeRepository $officeRepository;

    public function __construct(OfficeRepository $officeRepository)
    {
        $this->officeRepository = $officeRepository;
    }

    public function all()
    {
        return $this->officeRepository->all();
    }

    public function find($id)
    {
        return $this->officeRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->officeRepository->create($data);
    }

    public function update($id, array $data)
    {
        $office = $this->officeRepository->find($id);
        if (!$office) {
            return null;
        }

        return $this->officeRepository->update($office, $data);
    }

    public function delete($id)
    {
        $office = $this->officeRepository->find($id);
        if (!$office) {
            return false;
        }

        return $this->officeRepository->delete($office);

    }
}
