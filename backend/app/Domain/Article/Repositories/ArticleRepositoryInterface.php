<?php

namespace App\Domain\Article\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

/**
 * Интерфейс для репозитория ArticleRepository.
 *
 * Определяет базовые операции CRUD для сущности Article.
 */
interface ArticleRepositoryInterface
{
    /**
     * Получить все статьи.
     *
     * @return Collection|Article[]
     */
    public function all();

    /**
     * Найти статью по ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function find(int $id): ?Article;

    /**
     * Создать новую статью.
     *
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article;

    /**
     * Обновить статью.
     *
     * @param Article $article
     * @param array $data
     * @return Article
     */
    public function update(Article $article, array $data): Article;

    /**
     * Удалить статью.
     *
     * @param Article $article
     * @return bool|null
     */
    public function delete(Article $article): ?bool;
}
