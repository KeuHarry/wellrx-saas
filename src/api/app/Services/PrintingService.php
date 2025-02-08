<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PrintingService
{
    /**
     * Generate a PDF for printing.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(string $view, array $data, string $filename = 'document.pdf')
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4');

        return $pdf;
    }

    /**
     * Show print preview.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPrintPreview(string $view, array $data)
    {
        return view($view, $data);
    }

    /**
     * Download PDF.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPdf(string $view, array $data, string $filename = 'document.pdf', string $paperSize = 'a4')
    {
        $pdf = $this->generatePdf($view, $data, $filename, $paperSize);

        return $pdf->download($filename);
    }
}
