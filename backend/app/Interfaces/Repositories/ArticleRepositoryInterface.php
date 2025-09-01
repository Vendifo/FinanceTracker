<?php

namespace App\Interfaces\Repositories;

use App\Models\Article;

interface ArticleRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Article $article , array $data);
    public function delete(Article $article);
}
