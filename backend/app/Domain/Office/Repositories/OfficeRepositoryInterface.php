<?php

namespace App\Interfaces\Repositories;

use App\Models\Office;

interface OfficeRepositoryInterface
{
    public function all();

    public function find($id);
    public function create(array $data);
    public function update(Office $office, array $data);
    public function delete(Office $office);
}
