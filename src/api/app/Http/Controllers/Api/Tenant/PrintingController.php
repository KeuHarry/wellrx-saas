<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\EntityType;
use App\Http\Controllers\ApiController;
use App\Services\PrescriptionService;
use App\Services\PrintingService;
use App\Services\SaleService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;

class PrintingController extends ApiController
{
    protected $printingService;

    protected $saleService;

    protected $settingService;

    protected $prescriptionService;

    public function __construct(
        PrintingService $printingService,
        SaleService $saleService,
        SettingService $settingService,
        PrescriptionService $prescriptionService
    ) {
        $this->printingService = $printingService;
        $this->saleService = $saleService;
        $this->settingService = $settingService;
        $this->prescriptionService = $prescriptionService;
    }

    public function printInvoice($data)
    {
        return $this->printingService->showPrintPreview('pdf.invoice', ['invoice' => $data]);
    }

    public function downloadInvoice($data, $invoiceName = 'invoice-downloaded')
    {
        return $this->printingService->downloadPdf('pdf.invoice', ['invoice' => $data], "{$invoiceName}.pdf");
    }

    public function printEntityInvoice(string $entity, int $entityId)
    {
        [$data, $template, $dataKey] = match ($entity) {
            EntityType::SALE->value => [
                $this->saleService->getWithMoreDetails($entityId),
                'pdf.posInvoice',
                EntityType::SALE->value,
            ],
            EntityType::PRESCRIPTION->value => [
                $this->prescriptionService->getById($entityId),
                'pdf.prescriptionInvoice',
                EntityType::PRESCRIPTION->value,
            ],
            default => throw new \InvalidArgumentException("Unsupported entity type: {$entity}")
        };
        $pharmacyDetails = $this->settingService->getPharmacyDetails();
        $pharmacyDetails['amountWords'] = $this->printingService->convertToWords($data->net_due);
        $user = Auth::user();

        return $this->printingService->showPrintPreview($template, [$dataKey => $data, 'pharmacy' => $pharmacyDetails, 'user' => $user]);
    }

    public function printSaleReceipt(int $saleId)
    {
        $sale = $this->saleService->getWithMoreDetails($saleId);
        $pharmacyDetails = $this->settingService->getPharmacyDetails();

        return $this->printingService->showPrintPreview('receipt.saleReceipt', ['sale' => $sale, 'pharmacy' => $pharmacyDetails]);
    }

    public function printProformaInvoice(string $entity, int $entityId)
    {
        [$data, $template, $dataKey] = match ($entity) {
            EntityType::SALE->value => [
                $this->saleService->getWithMoreDetails($entityId),
                'pdf.proformaInvoice',
                EntityType::SALE->value,
            ],
            EntityType::PRESCRIPTION->value => [
                $this->prescriptionService->getById($entityId),
                'pdf.prescriptionProforma',
                EntityType::PRESCRIPTION->value,
            ],
            default => throw new \InvalidArgumentException("Unsupported entity type: {$entity}")
        };
        $pharmacyDetails = $this->settingService->getPharmacyDetails();
        $pharmacyDetails['amountWords'] = $this->printingService->convertToWords($data->net_due);
        $user = Auth::user();

        return $this->printingService->showPrintPreview($template, [$dataKey => $data, 'pharmacy' => $pharmacyDetails, 'user' => $user]);
    }
}
