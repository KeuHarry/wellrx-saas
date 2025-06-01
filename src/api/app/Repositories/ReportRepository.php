<?php

namespace App\Repositories;

use App\Enums\SaleStatus;
use App\Enums\SortBy;
use App\Models\Tenant\Product;
use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function __construct() {}

    public function paidSalesReport(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'DESC',
        string $sortBy = SortBy::UPDATED_AT->value,
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        $query = DB::table('sale_items as si')
            ->join('sales as s', 'si.sale_id', '=', 's.id')
            ->join('products as p', 'si.product_id', '=', 'p.id')
            ->join('inventories as i', 'si.id', '=', 'i.sale_item_id')
            ->join('users as u', 's.created_by', '=', 'u.id')
            ->join('users as cashier', 's.cashier_id', '=', 'cashier.id')
            ->select(
                'si.id',
                's.updated_at as paidDate',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as salesman"),
                DB::raw("CONCAT(cashier.first_name, ' ', cashier.last_name) as cashier"),
                'p.label as product',
                'i.before_qoh as quantityBefore',
                'i.after_qoh as quantityAfter',
                'si.quantity as quantitySold',
                'p.qoh as qoh',
                'si.price as price',
                'si.total as saleItemTotal'
            )
            ->where('s.status', SaleStatus::PAID->value) // Include both statuses
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('s.updated_at', [$fromDate, $toDate]);
            });

        $total = $query->count();
        $paidSalesReport = $query->offset($offset)
            ->limit($limit)
            ->orderBy('s.'.$sortBy, $sortOrder)
            ->get();

        return [
            'paidSalesReport' => $paidSalesReport,
            'total' => $total,
        ];
    }

    public function profitLossData(?string $fromDate = null, ?string $toDate = null): array
    {
        $salesCosts = DB::table('sales as s')
            ->select(
                DB::raw('SUM(s.net_due) as totalRevenue'),
                DB::raw('SUM(s.taxes) as totalTaxes')
            )
            ->where('s.status', SaleStatus::PAID->value)
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $fromDate = $fromDate.' 00:00:00';
                $toDate = $toDate.' 23:59:59';
                $query->whereBetween('s.updated_at', [$fromDate, $toDate]);
            })
            ->first();

        $saleItemCost = DB::table('sale_items as si')
            ->join('sales as s', 'si.sale_id', '=', 's.id')
            ->join('products as p', 'si.product_id', '=', 'p.id')
            ->select(
                DB::raw('SUM(p.acq_cost * si.quantity) as totalAcqCost')
            )
            ->where('s.status', SaleStatus::PAID->value)
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $fromDate = $fromDate.' 00:00:00';
                $toDate = $toDate.' 23:59:59';
                $query->whereBetween('s.updated_at', [$fromDate, $toDate]);
            })
            ->first();

        return [
            'totalRevenue' => round($salesCosts->totalRevenue, 2),
            'totalAcqCost' => round($saleItemCost->totalAcqCost, 2),
            'totalTaxes' => round($salesCosts->totalTaxes, 2),
            'grossProfit' => round($salesCosts->totalRevenue - $saleItemCost->totalAcqCost, 2),
            'netProfit' => round($salesCosts->totalRevenue - $saleItemCost->totalAcqCost - $salesCosts->totalTaxes, 2),
        ];
    }
}
