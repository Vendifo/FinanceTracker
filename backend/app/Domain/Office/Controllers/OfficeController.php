<?php

namespace App\Domain\Office\Controllers;

use App\Domain\Office\Services\OfficeServiceInterface;
use App\Domain\Office\Requests\StoreOfficeRequest;
use App\Domain\Office\Requests\UpdateOfficeRequest;
use App\Domain\Office\Resources\OfficeResource;
use Illuminate\Http\JsonResponse;

use App\Core\BaseController;
/**
 * Контроллер для работы с офисами
 */
class OfficeController extends BaseController
{
    /**
     * Сервис для работы с офисами
     */
    protected OfficeServiceInterface $officeService;

    /**
     * Внедрение зависимости через конструктор
     *
     * @param OfficeServiceInterface $officeService
     */
    public function __construct(OfficeServiceInterface $officeService)
    {
        $this->officeService = $officeService;
    }

    /**
     * Получить список всех офисов
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $offices = $this->officeService->all();
        return response()->json(OfficeResource::collection($offices));
    }

    /**
     * Показать конкретный офис по ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $office = $this->officeService->find($id);
        if (!$office) {
            return response()->json(['message' => 'Office not found'], 404);
        }

        return response()->json(new OfficeResource($office), 200);
    }

    /**
     * Создать новый офис
     *
     * @param StoreOfficeRequest $request
     * @return JsonResponse
     */
    public function store(StoreOfficeRequest $request): JsonResponse
    {
        $office = $this->officeService->create($request->validated());
        return response()->json(new OfficeResource($office), 201);
    }

    /**
     * Обновить офис
     *
     * @param UpdateOfficeRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOfficeRequest $request, int $id): JsonResponse
    {
        $office = $this->officeService->update($id, $request->validated());

        if (!$office) {
            return response()->json(['message' => 'Office not found'], 404);
        }

        return response()->json(new OfficeResource($office), 200);
    }

    /**
     * Удалить офис
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->officeService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Office not found'], 404);
        }

        return response()->json(['message' => 'Office deleted']);
    }
}
