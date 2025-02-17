<?php

namespace App\Services;

use App\Enums\CashierStatus;
use App\Enums\InventoryType;
use App\Enums\SaleStatus;
use App\Http\Resources\SaleResource;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Inventory;
use App\Models\Tenant\Product;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SalePayment;
use App\Repositories\CashRegisterRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\ReportRepository;
use App\Repositories\SaleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleService extends ApiService
{
    public function __construct(
        private SaleRepository $saleRepository,
        private InventoryRepository $inventoryRepository,
        private SaleItemService $saleItemService,
        private ProductService $productService,
        private InventoryService $inventoryService,
        private CashRegisterService $cashRegisterService,
        private SalePaymentService $salePaymentService,
        private CashRegisterRepository $cashRegisterRepository,
        private CustomerService $customerService,
        private ReportRepository $reportRepository
    ) {
        $this->saleRepository = $saleRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->inventoryService = $inventoryService;
        $this->cashRegisterService = $cashRegisterService;
        $this->salePaymentService = $salePaymentService;
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->customerService = $customerService;
        $this->reportRepository = $reportRepository;
        parent::__construct($saleRepository);
    }

    public function getSales(): Collection
    {
        return Sale::all();
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
        return $this->saleRepository->search(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy,
            $type,
            $status,
            $fromDate,
            $toDate,
            $cashierStatus
        );
    }

    public function getUnPaidSales(
        int $offset = 0,
        int $limit = 10,
        string $sortOrder = 'ASC',
        ?string $search = null,
        ?string $sortBy = null,
        ?int $type = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?int $cashierStatus = null
    ): AnonymousResourceCollection {
        $sales = $this->saleRepository->searchUnPaid(
            $offset,
            $limit,
            $sortOrder,
            $search,
            $sortBy,
            $type,
            $fromDate,
            $toDate,
            $cashierStatus
        );

        return SaleResource::collection($sales);
    }

    public function createSale($content): Sale
    {
        $saleItems = $content['saleItems'];
        unset($content['saleItems']);

        $sale = DB::transaction(function () use ($saleItems, $content) {
            $saleId = $content['saleId'] ? intval($content['saleId']) : null;
            $content['completedDate'] = intval($content['status']) === SaleStatus::COMPLETED->value ? now() : null;

            if ($saleId) {
                $this->update($saleId, $content);
                $createdSale = $this->getById($saleId);
                assert($createdSale instanceof Sale);
                $createdSale->saleItems()->delete();
            } else {
                $createdSale = $this->create($content);
            }
            assert($createdSale instanceof Sale);

            if ($createdSale->id) {
                $saleItems = array_map(function ($saleItem) use ($createdSale) {
                    $saleItem['saleId'] = $createdSale->id;

                    return $saleItem;
                }, $saleItems);
                $this->saleItemService->bulkCreate($saleItems);

                if ($createdSale->status === SaleStatus::COMPLETED->value && $createdSale->customer_id && ! $createdSale->is_one_time_payment) {
                    $this->customerService->updatePurchasedTotal($createdSale->customer_id);
                    $this->customerService->addBalance($createdSale->customer_id, $createdSale->net_due);
                }

                $createdSale->refresh();
                if ($createdSale->status === SaleStatus::COMPLETED->value && $createdSale->is_deducted) {
                    $this->processSaleItems($createdSale->saleItems);
                }
            }

            return $createdSale;
        });
        assert($sale instanceof Sale);

        return $sale;
    }

    public function getWithMoreDetails(int $id): Sale
    {
        return $this->saleRepository->findWithMoreDetails($id);
    }

    public function paySale($content): Sale
    {
        $sale = DB::transaction(function () use ($content) {
            $saleId = intval($content['saleId']);
            $isOneTimePayment = boolval($content['isOneTimePayment']);
            $sale = $this->getById($saleId);

            if ($isOneTimePayment) {
                $this->performPayment($sale, $content);
                $canDeduct = $sale->is_deducted;
                if ($sale->status === SaleStatus::PAID->value && $canDeduct) {
                    $this->processSaleItems($sale->saleItems);
                }
            }

            return $sale;
        });
        assert($sale instanceof Sale);

        return $sale;
    }

    public function performPartialPayment($content): Sale
    {
        $saleUpdated = DB::transaction(function () use ($content) {
            $saleId = intval($content['saleId']);
            $amount = floatval($content['amount']);
            $sale = $this->getById($saleId);
            $amount = floatval($sale->paid_amount) + $amount;
            $status = $amount >= $sale->net_due ? SaleStatus::PAID->value : SaleStatus::COMPLETED->value;
            $cashierStatus = $amount >= $sale->net_due ? CashierStatus::PAID->value : CashierStatus::IN_PROGRESS->value;

            if ($content['amount'] > 0 && $content['tendered'] > 0) {
                $saleContent = [
                    'paid_amount' => $amount,
                    'is_one_time_payment' => false,
                    'status' => $status,
                    'cashier_status' => $cashierStatus,
                ];
                $sale->update($saleContent);
                $sale->refresh();

                $content['cashRegisterId'] = $this->cashRegisterRepository->getCurrentCashRegisterId();
                $authUser = Auth::user();
                $content['cashierId'] = $authUser ? $authUser->id : 1;
                $salePayment = $this->salePaymentService->create($content);
                if (! $salePayment instanceof SalePayment) {
                    throw new \Exception("Can't perform payment");
                }
                $this->cashRegisterService->updateCurrentCashRegisterAmount(floatval($content['amount']));
                $customer = $sale->customer;
                assert($customer instanceof Customer);
                $customer->reduceBalance(floatval($content['amount']));
                $customer->addDeposit(floatval($content['amount']));
            }

            return $sale;
        });
        assert($saleUpdated instanceof Sale);

        return $saleUpdated;
    }

    private function performPayment(Sale $sale, array $content): void
    {
        $content['status'] = SaleStatus::PAID->value;
        $content['cashierStatus'] = CashierStatus::PAID->value;
        $authUser = Auth::user();
        $content['cashierId'] = $authUser ? $authUser->id : 1;
        $content['isDeducted'] = true;
        $content = $this->updateKey($content);
        $sale->update($content);
        $sale->refresh();

        $this->cashRegisterService->updateCurrentCashRegisterAmount($sale->net_due);
    }

    private function processSaleItems($saleItems)
    {
        foreach ($saleItems as $saleItem) {
            $this->processInventoryForItem($saleItem);
        }
    }

    private function processInventoryForItem($saleItem): void
    {
        $productId = intval($saleItem->product_id);
        $quantityToSell = intval($saleItem->quantity);
        $product = $this->productService->getById($productId);

        if (! $product instanceof Product) {
            throw new \Exception('Invalid product');
        }

        $batches = $this->inventoryRepository->getAvailableBatches($product->id);

        foreach ($batches as $batch) {
            if ($quantityToSell <= 0) {
                break;
            }
            assert($batch instanceof Inventory);
            $quantityToSell = $this->deductQuantityFromBatch($saleItem->id, $batch, $quantityToSell);
        }

        if ($quantityToSell > 0) {
            $this->handleOutOfStock($saleItem->id, $product, $quantityToSell);
        }

        $product->qoh -= intval($saleItem->quantity);
        $product->save();
    }

    private function deductQuantityFromBatch(int $saleItemId, Inventory $batch, int $quantityToSell): int
    {
        $deductedQuantity = min($batch->actual_quantity, $quantityToSell);
        $batch->actual_quantity -= $deductedQuantity;
        $batch->save();

        $newInventoryEntry = [];
        $newInventoryEntry['productId'] = $batch->product_id;
        $newInventoryEntry['quantity'] = -$deductedQuantity;
        $newInventoryEntry['type'] = InventoryType::SELL->value;
        $newInventoryEntry['expiryDate'] = $batch->expiry_date;
        $newInventoryEntry['batchId'] = $batch->batch_id;
        $newInventoryEntry['acqCost'] = $batch->acq_cost;
        $newInventoryEntry['wholesaleCost'] = $batch->wholesale_cost;
        $newInventoryEntry['retailCost'] = $batch->retail_cost;
        $newInventoryEntry['unitCost'] = $batch->unit_cost;
        $newInventoryEntry['supplierId'] = $batch->supplier_id;
        $newInventoryEntry['saleItemId'] = $saleItemId;
        $this->inventoryService->create($newInventoryEntry);

        $quantityToSell -= $deductedQuantity;

        return $quantityToSell;
    }

    private function handleOutOfStock(int $saleItemId, Product $product, int $quantityToSell): void
    {
        $newInventoryEntry = [];
        $newInventoryEntry['productId'] = $product->id;
        $newInventoryEntry['quantity'] = -$quantityToSell;
        $newInventoryEntry['type'] = InventoryType::SELL->value;
        $newInventoryEntry['expiryDate'] = now()->addYear(1);
        $newInventoryEntry['batchId'] = 'OOS-'.$product->product_code;
        $newInventoryEntry['acqCost'] = $product->acq_cost;
        $newInventoryEntry['wholesaleCost'] = $product->wholesale_cost;
        $newInventoryEntry['retailCost'] = $product->retail_cost;
        $newInventoryEntry['unitCost'] = $product->unit_cost;
        $newInventoryEntry['saleItemId'] = $saleItemId;
        $this->inventoryService->create($newInventoryEntry);
    }
}
