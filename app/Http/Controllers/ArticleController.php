<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::paginate(15);
        return ArticleResource::collection($articles);
    }

    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->validated());
        return new ArticleResource($article);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->validated());
        return new ArticleResource($article);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(['message' => 'Artículo eliminado correctamente'], 200);
    }
}
