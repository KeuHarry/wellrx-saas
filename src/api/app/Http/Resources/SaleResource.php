<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'items' => count($this->saleItems),
            'netDue' => $this->net_due,
            'status' => $this->status,
            'cashierStatus' => $this->cashier_status,
            'type' => $this->type,
            'createdAt' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
