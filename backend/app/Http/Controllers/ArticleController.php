<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ArticleServiceInterface;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для работы с сущностью Article
 *
 * Отвечает за:
 * - CRUD статьи
 * - Возврат JSON-ответов
 * - Делегирование бизнес-логики сервису
 */
class ArticleController extends Controller
{
    protected ArticleServiceInterface $articleService;

    /**
     * Внедрение сервиса через конструктор
     *
     * @param ArticleServiceInterface $articleService
     */
    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Получить список всех статей
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Возвращаем коллекцию статей через ресурс
        return ArticleResource::collection($this->articleService->all());
    }

    /**
     * Показать одну статью по ID
     *
     * @param int $id
     * @return ArticleResource|JsonResponse
     */
    public function show(int $id)
    {
        $article = $this->articleService->find($id);

        // Если статья не найдена, возвращаем 404
        return $article
            ? new ArticleResource($article)
            : $this->notFoundResponse();
    }

    /**
     * Создать новую статью
     *
     * @param StoreArticleRequest $request
     * @return ArticleResource
     */
    public function store(StoreArticleRequest $request)
    {
        // Создаём статью через сервис
        $article = $this->articleService->create($request->validated());

        // Возвращаем ресурс с кодом 201 (создано)
        return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    /**
     * Обновить существующую статью
     *
     * @param UpdateArticleRequest $request
     * @param int $id
     * @return ArticleResource|JsonResponse
     */
    public function update(UpdateArticleRequest $request, int $id)
    {
        $article = $this->articleService->update($id, $request->validated());

        return $article
            ? new ArticleResource($article)
            : $this->notFoundResponse();
    }

    /**
     * Удалить статью
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->articleService->delete($id);

        return $deleted
            ? response()->json(['message' => 'Article deleted'], 200)
            : $this->notFoundResponse();
    }

    /**
     * Универсальный ответ при ошибке 404
     *
     * @param string $message
     * @return JsonResponse
     */
    private function notFoundResponse(string $message = 'Article not found'): JsonResponse
    {
        return response()->json(['message' => $message], 404);
    }
}
