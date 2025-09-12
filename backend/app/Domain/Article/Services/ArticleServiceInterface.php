<?php

namespace App\Interfaces\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

/**
 * Интерфейс для ArticleService.
 *
 * Определяет бизнес-логику для работы со статьями.
 * Методы сервиса оборачивают доступ к репозиторию
 * и позволяют добавлять дополнительные проверки,
 * транзакции и бизнес-правила.
 */
interface ArticleServiceInterface
{
    /**
     * Получить список всех статей.
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
     * Обновить статью по ID.
     *
     * @param int $id
     * @param array $data
     * @return Article|null
     */
    public function update(int $id, array $data): ?Article;

    /**
     * Удалить статью по ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
