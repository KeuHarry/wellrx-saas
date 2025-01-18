<?php

namespace App\Services;

use App\Models\Tenant\Patient;
use App\Repositories\PatientRepository;
use Illuminate\Database\Eloquent\Collection;

class PatientService extends ApiService
{
    public function __construct(private PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
        parent::__construct($patientRepository);
    }

    public function getPatients(): Collection
    {
        return Patient::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->patientRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
