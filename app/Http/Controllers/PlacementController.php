<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use App\Http\Requests\StorePlacementRequest;
use App\Http\Requests\UpdatePlacementRequest;
use App\Http\Resources\PlacementResource;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Placement::with('article');
        
        // Filtrar por artículo
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }
        
        // Filtrar por ubicación
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }
        
        // Filtrar por rango de precio
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Filtrar por nombre de colocación
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        
        $placements = $query->paginate(15);
        
        return PlacementResource::collection($placements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlacementRequest $request)
    {
        $placement = Placement::create($request->validated());
        $placement->load('article');
        
        return new PlacementResource($placement);
    }

    /**
     * Display the specified resource.
     */
    public function show(Placement $placement)
    {
        $placement->load('article');
        return new PlacementResource($placement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlacementRequest $request, Placement $placement)
    {
        $placement->update($request->validated());
        $placement->load('article');
        
        return new PlacementResource($placement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Placement $placement)
    {
        $placement->delete();
        
        return response()->json([
            'message' => 'Placement deleted successfully'
        ]);
    }
}