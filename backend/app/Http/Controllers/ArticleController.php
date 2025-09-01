<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ArticleServiceInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected ArticleServiceInterface $articleService;

    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index()
    {
        return response()->json($this->articleService->all());
    }

    public function show($id)
    {
        $article = $this->articleService->find($id);
        if (!$article) return response()->json(['message' => 'Article not found'], 404);
        return response()->json($article, 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $article = $this->articleService->create($request->only('name'));
        return response()->json($article, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $article = $this->articleService->update($id,$request->only('name'));

        if (!$article) return response()->json(['message' => 'Article not found'], 404);
        return response()->json($article, 200);

    }

    public function destroy($id)
    {
        $deleted = $this->articleService->delete($id);
        if (!$deleted) return response()->json(['message' => 'Article not found'], 404);
        return response()->json(['message' => 'Article deleted'], 200);
    }
}
