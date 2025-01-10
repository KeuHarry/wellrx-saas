<?php

namespace App\Services;

use App\Models\Tenant\Intermediary;
use App\Repositories\IntermediaryRepository;
use Illuminate\Database\Eloquent\Collection;

class IntermediaryService extends ApiService
{
    public function __construct(private IntermediaryRepository $intermediaryRepository)
    {
        $this->intermediaryRepository = $intermediaryRepository;
        parent::__construct($intermediaryRepository);
    }

    public function getIntermediaries(): Collection
    {
        return Intermediary::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->intermediaryRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
