<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InStockProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->label,
            'qoh' => $this->qoh,
            'wholesaleCost' => $this->wholesale_cost,
            'retailCost' => $this->retail_cost,
            'tax' => $this->fee ? [
                'id' => $this->fee->id,
                'value' => $this->fee->value,
                'type' => $this->fee->type,
            ] : null,
            'shelf' => $this->shelf->name ?? null,
            'inventories' => InStockInventoryResource::collection($this->whenLoaded('inventories')),
        ];
    }
}
