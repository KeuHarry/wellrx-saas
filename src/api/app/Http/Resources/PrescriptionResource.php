<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'prescriptionCode' => $this->prescription_code,
            'patient' => [
                'id' => $this->patient->id,
                'firstName' => $this->patient->first_name,
                'lastName' => $this->patient->last_name,
            ],
            'prescriber' => [
                'id' => $this->prescriber->id,
                'firstName' => $this->prescriber->first_name,
                'lastName' => $this->prescriber->last_name,
            ],
            'total' => $this->total,
            'netDue' => $this->net_due,
            'status' => $this->status,
            'prescriptionDate' => $this->prescription_date,
        ];
    }
}
