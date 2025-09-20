<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['client', 'article', 'placement']);
        
        // Filtrar por cliente
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        // Filtrar por artículo
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }
        
        // Filtrar por colocación
        if ($request->filled('placement_id')) {
            $query->where('placement_id', $request->placement_id);
        }
        
        // Filtrar por rango de fechas
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filtrar por cantidad mínima
        if ($request->filled('quantity_min')) {
            $query->where('quantity', '>=', $request->quantity_min);
        }
        
        $purchases = $query->paginate(15);
        
        return PurchaseResource::collection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     * Implementa la lógica de acumulación: si existe una compra del mismo artículo
     * en la misma colocación por el mismo cliente, se acumula la cantidad.
     */
    public function store(StorePurchaseRequest $request)
    {
        $validated = $request->validated();
        
        // Buscar compra existente del mismo cliente, artículo y colocación
        $existingPurchase = Purchase::where([
            'client_id' => $validated['client_id'],
            'article_id' => $validated['article_id'],
            'placement_id' => $validated['placement_id'],
        ])->first();
        
        if ($existingPurchase) {
            // Acumular cantidad y recalcular precio total
            $existingPurchase->quantity += $validated['quantity'];
            $existingPurchase->total_price = $existingPurchase->quantity * $existingPurchase->unit_price;
            $existingPurchase->save();
            
            $existingPurchase->load(['client', 'article', 'placement']);
            return response()->json(['data' => new PurchaseResource($existingPurchase)], 201);
        }
        
        // Crear nueva compra si no existe una previa
        $purchase = Purchase::create($validated);
        $purchase->load(['client', 'article', 'placement']);
        
        return response()->json(['data' => new PurchaseResource($purchase)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['client', 'article', 'placement']);
        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $purchase->update($request->validated());
        $purchase->load(['client', 'article', 'placement']);
        
        return new PurchaseResource($purchase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        
        return response()->json([
            'message' => 'Purchase deleted successfully'
        ]);
    }
}