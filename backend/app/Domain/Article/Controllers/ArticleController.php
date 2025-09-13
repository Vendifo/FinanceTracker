<?php

namespace App\Domain\Article\Controllers;

use App\Domain\Article\Services\ArticleServiceInterface;
use App\Domain\Article\Requests\StoreArticleRequest;
use App\Domain\Article\Requests\UpdateArticleRequest;
use App\Domain\Article\Resources\ArticleResource;

use Illuminate\Http\JsonResponse;

use App\Core\BaseController;

class ArticleController extends BaseController
{
    protected ArticleServiceInterface $articleService;

    /**
     * Внедрение сервиса статей через конструктор
     *
     * @param ArticleServiceInterface $articleService
     */
    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Получение списка всех статей
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = $this->articleService->all();

        return $this->apiResponse(
            ArticleResource::collection($articles),
            'Articles retrieved successfully'
        );
    }

    /**
     * Показ одной статьи по ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->find($id);

        if (!$article) {
            return $this->apiResponse(null, 'Article not found', false, 404);
        }

        return $this->apiResponse(new ArticleResource($article), 'Article retrieved successfully');
    }

    /**
     * Создание новой статьи
     *
     * @param StoreArticleRequest $request
     * @return JsonResponse
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->create($request->validated());

        return $this->apiResponse(
            new ArticleResource($article),
            'Article created successfully',
            true,
            201
        );
    }

    /**
     * Обновление существующей статьи
     *
     * @param UpdateArticleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $article = $this->articleService->update($id, $request->validated());

        if (!$article) {
            return $this->apiResponse(null, 'Article not found', false, 404);
        }

        return $this->apiResponse(new ArticleResource($article), 'Article updated successfully');
    }

    /**
     * Удаление статьи
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->articleService->delete($id);

        if (!$deleted) {
            return $this->apiResponse(null, 'Article not found', false, 404);
        }

        return $this->apiResponse(null, 'Article deleted');
    }
}
