<?php

namespace App\Services;

use App\Enums\CashierStatus;
use App\Enums\PrescriptionStatus;
use App\Enums\SaleStatus;
use App\Enums\SortBy;
use App\Models\Tenant\Prescription;
use App\Repositories\PrescriptionRepository;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

class PrescriptionService extends ApiService
{
    public function __construct(
        private PrescriptionRepository $prescriptionRepository,
        private SaleRepository $saleRepository,
        private SaleItemService $saleItemService,
        private ProductService $productService,
        private CashRegisterService $cashRegisterService,
        private PrescriptionItemService $prescriptionItemService,
        private SaleService $saleService
    ) {
        $this->prescriptionRepository = $prescriptionRepository;
        $this->saleRepository = $saleRepository;
        $this->cashRegisterService = $cashRegisterService;
        $this->prescriptionItemService = $prescriptionItemService;
        $this->saleService = $saleService;
        parent::__construct($prescriptionRepository);
    }

    public function search(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        string $sortBy = SortBy::CREATED_AT->value,
        ?string $search = null,
        ?int $status = null,
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        return $this->prescriptionRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $sortBy,
            $search,
            $status,
            $fromDate,
            $toDate
        );
    }

    public function createPrescription(array $content): Prescription
    {
        $prescription = DB::transaction(function () use ($content) {
            $prescriptionItems = $content['prescriptionItems'];
            unset($content['prescriptionItems']);

            $createdPrescription = $this->create($content);
            if (! $createdPrescription instanceof Prescription) {
                throw new \RuntimeException('Prescription not created');
            }

            if ($createdPrescription->id) {
                $prescriptionItems = array_map(function ($prescriptionItem) use ($createdPrescription) {
                    $prescriptionItem['prescriptionId'] = $createdPrescription->id;

                    return $prescriptionItem;
                }, $prescriptionItems);
                $this->prescriptionItemService->bulkCreate($prescriptionItems);
            }
            $createdPrescription->refresh();

            if (intval($content['status']) === PrescriptionStatus::COMPLETED->value) {
                $this->createSaleForPrescription($createdPrescription);
            }

            return $createdPrescription;
        });
        assert($prescription instanceof Prescription);

        return $prescription;
    }

    public function updatePrescription(int $id, array $content): int
    {
        $prescriptionId = DB::transaction(function () use ($id, $content) {
            $prescriptionItems = $content['prescriptionItems'] ?? [];
            unset($content['prescriptionItems']);

            $this->update($id, $content);
            $prescription = $this->getById($id);
            if (! $prescription instanceof Prescription) {
                throw new \RuntimeException('Prescription not found');
            }

            $this->updatePrescriptionItems($prescription, $prescriptionItems);
            $prescription->refresh();

            if (intval($content['status']) === PrescriptionStatus::COMPLETED->value) {
                $this->createSaleForPrescription($prescription);
            }

            return $prescription->id;
        });

        return intval($prescriptionId);
    }

    public function prepareSaleData(Prescription $prescription): array
    {
        $prescriptionItems = $prescription->prescriptionItems;
        $sale = [];
        $saleItems = [];
        $sale['saleId'] = null;
        $sale['prescriptionId'] = $prescription->id;
        $sale['status'] = SaleStatus::COMPLETED->value;
        $sale['cashierStatus'] = CashierStatus::PENDING->value;
        $sale['total'] = round($prescription->total - $prescription->taxes, 2);
        $sale['netDue'] = $prescription->net_due;
        $sale['discount'] = $prescription->discount_flat + $prescription->insurance_flat;
        $sale['taxes'] = $prescription->taxes;

        foreach ($prescriptionItems as $prescriptionItem) {
            $saleItems[] = [
                'productId' => $prescriptionItem->product_id,
                'quantity' => $prescriptionItem->quantity,
                'price' => $prescriptionItem->cost,
                'tax' => $prescriptionItem->tax,
                'fee' => $prescriptionItem->fee,
                'total' => round($prescriptionItem->total - ($prescriptionItem->tax * $prescriptionItem->quantity) - $prescriptionItem->fee, 2),
                'prescriptionItemId' => $prescriptionItem->id,
            ];
        }
        $sale['saleItems'] = $saleItems;

        return $sale;
    }

    /**
     * Update prescription items.
     */
    private function updatePrescriptionItems(Prescription $prescription, array $items): void
    {
        // Delete existing items
        $prescription->prescriptionItems()->delete();

        // Add new items
        if (! empty($items)) {
            $items = array_map(function ($item) use ($prescription) {
                $item['prescriptionId'] = $prescription->id;

                return $item;
            }, $items);

            $this->prescriptionItemService->bulkCreate($items);
        }
    }

    /**
     * Create a sale for a completed prescription.
     */
    private function createSaleForPrescription(Prescription $prescription): void
    {
        $saleData = $this->prepareSaleData($prescription);
        $this->saleService->createSale($saleData);
    }
}
