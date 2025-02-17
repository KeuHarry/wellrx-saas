<?php

namespace App\Services;

use App\Enums\SortBy;
use App\Repositories\ReportRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository
    ) {
        $this->reportRepository = $reportRepository;
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
}
