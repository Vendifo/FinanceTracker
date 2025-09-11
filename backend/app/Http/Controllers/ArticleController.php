<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ArticleServiceInterface;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\JsonResponse;

class ArticleController extends ApiController
{
    protected ArticleServiceInterface $articleService;

    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Получить список всех статей
     */
    public function index(): JsonResponse
    {
        $articles = $this->articleService->all();

        return $this->apiResponse(
            ArticleResource::collection($articles),
            'Список статей получен'
        );
    }

    /**
     * Показать одну статью
     */
    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->find($id);

        if (!$article) {
            return $this->apiResponse(null, 'Статья не найдена', false, 404);
        }

        return $this->apiResponse(new ArticleResource($article), 'Статья найдена');
    }

    /**
     * Создать статью
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->create($request->validated());

        return $this->apiResponse(
            new ArticleResource($article),
            'Статья создана',
            true,
            201
        );
    }

    /**
     * Обновить статью
     */
    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $article = $this->articleService->update($id, $request->validated());

        if (!$article) {
            return $this->apiResponse(null, 'Статья не найдена', false, 404);
        }

        return $this->apiResponse(new ArticleResource($article), 'Статья обновлена');
    }

    /**
     * Удалить статью
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->articleService->delete($id);

        if (!$deleted) {
            return $this->apiResponse(null, 'Статья не найдена', false, 404);
        }

        return $this->apiResponse(null, 'Статья удалена');
    }
}
