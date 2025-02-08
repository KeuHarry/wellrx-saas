<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Enums\SettingKey;
use App\Http\Controllers\ApiController;
use App\Services\PrintingService;
use App\Services\SaleService;
use App\Services\SettingService;

class PrintingController extends ApiController
{
    protected $printingService;

    protected $saleService;

    protected $settingService;

    public function __construct(PrintingService $printingService, SaleService $saleService, SettingService $settingService)
    {
        $this->printingService = $printingService;
        $this->saleService = $saleService;
        $this->settingService = $settingService;
    }

    public function printInvoice($data)
    {
        return $this->printingService->showPrintPreview('pdf.invoice', ['invoice' => $data]);
    }

    public function downloadInvoice($data, $invoiceName = 'invoice-downloaded')
    {
        return $this->printingService->downloadPdf('pdf.invoice', ['invoice' => $data], "{$invoiceName}.pdf");
    }

    public function printSaleInvoice(int $saleId)
    {
        $sale = $this->saleService->getWithMoreDetails($saleId);
        $currency = $this->settingService->getByKey(SettingKey::CURRENCY->value);

        return $this->printingService->showPrintPreview('pdf.posInvoice', ['sale' => $sale, 'currency' => $currency]);
    }

    public function printSaleReceipt(int $saleId)
    {
        $sale = $this->saleService->getWithMoreDetails($saleId);
        $pharmacyDetails = $this->settingService->getPharmacyDetails();

        return $this->printingService->showPrintPreview('receipt.saleReceipt', ['sale' => $sale, 'pharmacy' => $pharmacyDetails]);
    }

    public function printProformaInvoice(int $saleId)
    {
        $sale = $this->saleService->getWithMoreDetails($saleId);
        $pharmacyDetails = $this->settingService->getPharmacyDetails();

        return $this->printingService->showPrintPreview('pdf.proformaInvoice', ['sale' => $sale, 'pharmacy' => $pharmacyDetails]);
    }
}
