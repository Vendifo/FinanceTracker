<?php

namespace App\Domain\Article\Repositories;

use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для работы с моделью Article.
 *
 * Отвечает только за доступ к данным (CRUD).
 * Вся бизнес-логика должна находиться в сервисе.
 */
class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * Получить все статьи.
     *
     * @return Collection|Article[]
     */
    public function all(): Collection
    {
        return Article::all();
    }

    /**
     * Найти статью по ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function find(int $id): ?Article
    {
        return Article::find($id);
    }

    /**
     * Создать новую статью.
     *
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article
    {
        return Article::create($data);
    }

    /**
     * Обновить существующую статью.
     *
     * @param Article $article
     * @param array $data
     * @return Article
     */
    public function update(Article $article, array $data): Article
    {
        $article->update($data);
        return $article;
    }

    /**
     * Удалить статью.
     *
     * @param Article $article
     * @return bool
     */
    public function delete(Article $article): bool
    {
        return (bool) $article->delete();
    }
}
