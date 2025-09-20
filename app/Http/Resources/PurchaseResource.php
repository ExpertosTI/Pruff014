<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'article_id' => $this->article_id,
            'article' => new ArticleResource($this->whenLoaded('article')),
            'placement_id' => $this->placement_id,
            'placement' => new PlacementResource($this->whenLoaded('placement')),
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unit_price, 2),
            'total_price' => number_format($this->total_price, 2),
            'purchase_date' => $this->created_at->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}