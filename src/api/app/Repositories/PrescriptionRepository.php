<?php

namespace App\Repositories;

use App\Enums\SortBy;
use App\Http\Resources\PrescriptionResource;
use App\Models\Tenant\Prescription;

class PrescriptionRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Prescription;
        parent::__construct();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        string $sortBy = SortBy::CREATED_AT->value,
        ?string $search = null,
        ?int $status = null,
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        $query = Prescription::when($search, function ($query, $search) {
            $query->where('prescription_code', 'LIKE', "%{$search}%")
                ->orWhereHas('patient', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$search}%"]);
                })
                ->orWhereHas('prescriber', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$search}%"]);
                });
        })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('prescription_date', [$fromDate, $toDate]);
            });

        $total = $query->count();
        $prescriptions = $query->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        return [
            'prescriptions' => PrescriptionResource::collection($prescriptions),
            'total' => $total,
        ];
    }

    public function prescriptionsCount(): int
    {
        return $this->findAll()->count();
    }
}
