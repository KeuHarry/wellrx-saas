<?php

namespace App\Services;

use App\Models\Tenant\Fee;
use App\Repositories\FeeRepository;
use Illuminate\Database\Eloquent\Collection;

class FeeService extends ApiService
{
    public function __construct(private FeeRepository $feeRepository)
    {
        $this->feeRepository = $feeRepository;
        parent::__construct($feeRepository);
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        ?string $search = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $tag = null
    ): array {
        $query = Fee::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('fee_code', 'LIKE', "%{$search}%");
            });
        })
            ->when($tag !== null, function ($query) use ($tag) {
                $query->where('tag', $tag);
            });

        $total = $query->count();
        $fees = $query->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        return [
            'fees' => $fees,
            'total' => $total,
        ];
    }

    public function getFeeByTag(int $tag): Collection
    {
        return $this->feeRepository->findFeeByTag($tag);
    }
}
