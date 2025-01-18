<?php

namespace App\Repositories;

use App\Models\Tenant\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Patient();
        parent::__construct();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return Patient::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$search}%"])
                        ->orWhere('patient_code', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
            });
        })
        ->offset($offset)
        ->limit($limit)
        ->orderBy($sortBy, $sortOrder)
        ->get();
    }
}
