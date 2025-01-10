<?php

namespace App\Services;

use App\Exceptions\InternalServerErrorException;
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
    ): Collection 
    {
        $fees = Fee::where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('tag', $tag)
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

        if (! assert($fees instanceof Collection)) {
            throw new InternalServerErrorException;
        }

        return $fees;
    }
}
