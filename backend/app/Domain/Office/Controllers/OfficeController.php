<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\OfficeServiceInterface;
use Illuminate\Http\Request;

class OfficeController extends BaseController
{
    protected OfficeServiceInterface $officeService;

    public function __construct(OfficeServiceInterface $officeService)
    {
        $this->officeService = $officeService;
    }

    public function index()
    {
        return response()->json($this->officeService->all());
    }

    public function show($id)
    {
        $office = $this->officeService->find($id);
        if (!$office) {
            return response()->json(['message' => 'Office not found']);
        }

        return response()->json($office, 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $office = $this->officeService->create($request->only('name'));
        return response()->json($office, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $office = $this->officeService->update($id, $request->only('name'));

        if (!$office) {
            return response()->json(['message' => 'Office not found']);
        }

        return response()->json($office, 200);
    }

    public function destroy($id)
    {
        $office = $this->officeService->delete($id);
        if (!$office) {
            return response()->json(['message' => 'Office not found']);
        }

        return response()->json(['message' => 'Office deleted']);
    }
}
