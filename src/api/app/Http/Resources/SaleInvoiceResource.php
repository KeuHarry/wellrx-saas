<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleInvoiceResource extends JsonResource
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
            'code' => $this->code,
            'total' => $this->total,
            'taxes' => $this->taxes,
            'taxes' => $this->discount,
            'netDue' => $this->net_due,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'saleItems' => SaleItemResource::collection($this->whenLoaded('saleItems')),
        ];
    }
}
