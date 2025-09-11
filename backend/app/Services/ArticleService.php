<?php

namespace App\Services;

use App\Interfaces\Services\ArticleServiceInterface;
use App\Repositories\ArticleRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

/**
 * Сервисный слой для работы с моделью Article.
 *
 * Здесь размещается бизнес-логика:
 * - Проверки перед CRUD операциями
 * - Возможность расширения (например, логирование, события, транзакции)
 * - Делегирование операций репозиторию
 */
class ArticleService implements ArticleServiceInterface
{
    protected ArticleRepository $articleRepository;

    /**
     * Внедрение зависимости репозитория через конструктор.
     *
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Получить список всех статей.
     *
     * @return Collection|Article[]
     */
    public function all(): Collection
    {
        return $this->articleRepository->all();
    }

    /**
     * Найти статью по ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function find(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }

    /**
     * Создать новую статью.
     *
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article
    {
        return $this->articleRepository->create($data);
    }

    /**
     * Обновить статью по ID.
     *
     * @param int $id
     * @param array $data
     * @return Article|null
     */
    public function update(int $id, array $data): ?Article
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            return null;
        }

        return $this->articleRepository->update($article, $data);
    }

    /**
     * Удалить статью по ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            return false;
        }

        return $this->articleRepository->delete($article);
    }
}
