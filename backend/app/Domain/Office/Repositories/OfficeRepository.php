<?php

namespace App\Repositories;

use App\Interfaces\Repositories\OfficeRepositoryInterface;
use App\Models\Office;

class OfficeRepository implements OfficeRepositoryInterface
{
    public function all()
    {
        return Office::all();
    }

    public function find($id)
    {
        return Office::find($id);
    }

    public function create(array $data)
    {
		return Office::create($data);
    }

    public function update(Office $office, array $data)
    {
        $office->update($data);
        return  $office;
    }

    public function delete(Office $office)
    {
        return $office->delete();
    }
}
