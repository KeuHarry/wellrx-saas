<?php

namespace App\Services;

use App\Enums\SortBy;
use App\Repositories\OrderRepository;
use App\Repositories\PrescriptionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReportRepository;
use App\Repositories\SaleItemRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository,
        private ProductRepository $productRepository,
        private OrderRepository $orderRepository,
        private SaleItemRepository $saleItemRepository,
        private PrescriptionRepository $prescriptionRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->saleItemRepository = $saleItemRepository;
        $this->prescriptionRepository = $prescriptionRepository;
    }

    public function getPaidSalesReport(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'DESC',
        string $sortBy = SortBy::UPDATED_AT->value,
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        return $this->reportRepository->paidSalesReport(
            $offset,
            $limit,
            $sortOrder,
            $sortBy,
            $fromDate,
            $toDate
        );
    }

    public function getProfitLossData(
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        return $this->reportRepository->profitLossData($fromDate, $toDate);
    }

    public function getDataCount(): array
    {
        $itemsSoldCount = $this->saleItemRepository->paidSaleItemsCount();
        $ordersCount = $this->orderRepository->ordersCount();
        $productsCount = $this->productRepository->productsCount();
        $prescriptionsCount = $this->prescriptionRepository->prescriptionsCount();

        return [
            'itemsSoldCount' => $itemsSoldCount,
            'ordersCount' => $ordersCount,
            'productsCount' => $productsCount,
            'prescriptionsCount' => $prescriptionsCount
        ];
    }
}
