<?php

namespace App\Services;

use App\Interfaces\Services\ArticleServiceInterface;
use App\Repositories\ArticleRepository;
use App\Models\Article;
class ArticleService implements ArticleServiceInterface
{

    protected ArticleRepository $articleRepository;
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function all()
    {
        return $this->articleRepository->all();
    }

    public function find($id)
    {
        return $this->articleRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->articleRepository->create($data);
    }

    public function update($id, array $data)
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return null;
        }

        return $this->articleRepository->update($article, $data);
    }

    public function delete($id)
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return false;
        }

        return $this->articleRepository->delete($article);
    }

}
