<?php

namespace App\Services;

use App\Models\Tenant\LabelCode;
use App\Repositories\LabelRepository;
use Illuminate\Database\Eloquent\Collection;

class LabelCodeService extends ApiService
{
    public function __construct(private LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
        parent::__construct($labelRepository);
    }

    public function getLabels(): Collection
    {
        return LabelCode::all();
    }

    public function search(int $offset = 0, int $limit = 10, string $sortOrder = 'ASC', ?string $search = null, ?string $sortBy = null): Collection
    {
        return $this->labelRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy
        );
    }
}
