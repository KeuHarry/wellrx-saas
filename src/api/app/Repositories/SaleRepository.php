<?php

namespace App\Repositories;

use App\Enums\SaleStatus;
use App\Http\Resources\SaleResource;
use App\Models\Tenant\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Sale;
        parent::__construct();
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $type = null,
        ?int $status = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?int $cashierStatus = null
    ): array {
        $query = Sale::when($search, function ($query, $search) {
            $query->where('code', 'LIKE', "%{$search}%");
        })
            ->when($type !== null, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($cashierStatus !== null, function ($query) use ($cashierStatus) {
                $query->where('cashier_status', $cashierStatus);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            });

        $total = $query->count();

        $sales = $query->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();

        return [
            'sales' => SaleResource::collection($sales),
            'total' => $total,
        ];
    }

    public function searchUnPaid(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $type = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?int $cashierStatus = null
    ): Collection {
        return Sale::where('status', '<>', SaleStatus::PAID->value)
            ->when($search, function ($query, $search) {
                $query->where('code', 'LIKE', "%{$search}%");
            })
            ->when($type !== null, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($cashierStatus !== null, function ($query) use ($cashierStatus) {
                $query->where('cashier_status', $cashierStatus);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy($sortBy, $sortOrder)
            ->get();
    }

    public function getNextId(): int
    {
        $lastSale = Sale::orderBy('id', 'desc')->first();

        return $lastSale ? $lastSale->id + 1 : 1;
    }

    public function findWithMoreDetails(int $id): Sale
    {
        $sale = Sale::with(['saleItems', 'saleItems.product:id,label'])->findOrFail($id);

        return $sale;
    }
}
