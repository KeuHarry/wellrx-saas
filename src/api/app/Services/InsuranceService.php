<?php

namespace App\Services;

use App\Models\Tenant\Insurer;
use App\Repositories\InsurerRepository;
use Illuminate\Database\Eloquent\Collection;

class InsuranceService extends ApiService
{
    public function __construct(private InsurerRepository $insurerRepository)
    {
        $this->insurerRepository = $insurerRepository;
        parent::__construct($insurerRepository);
    }

    public function getInsurers(): Collection
    {
        return Insurer::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->insurerRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
