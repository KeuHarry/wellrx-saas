<?php

namespace App\Services;

use App\Models\Tenant\Prescriber;
use App\Repositories\PrescriberRepository;
use Illuminate\Database\Eloquent\Collection;

class PrescriberService extends ApiService
{
    public function __construct(private PrescriberRepository $prescriberRepository)
    {
        $this->prescriberRepository = $prescriberRepository;
        parent::__construct($prescriberRepository);
    }

    public function getPrescribers(): Collection
    {
        return Prescriber::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->prescriberRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
