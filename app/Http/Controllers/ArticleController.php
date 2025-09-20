<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Buscar artículos es como buscar cassettes en una caja: hay que tener paciencia
    public function index(Request $request)
    {
        $query = Article::query();
        
        // Filtrar por fabricante es como elegir solo los discos de Sony
        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', $request->manufacturer);
        }
        
        // Buscar en descripción es como leer las letras pequeñas del CD
        if ($request->filled('description')) {
            $query->where('description', 'LIKE', '%' . $request->description . '%');
        }
        
        // Paginar es como cambiar de lado el cassette: 15 canciones por lado
        $articles = $query->paginate(15);
        
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
