<?php

namespace Vanguard\Services;

use Illuminate\Support\Facades\Storage;
use Spipu\Html2Pdf\Html2Pdf;
use setasign\Fpdi\Tcpdf\Fpdi;

class DocumentAcknowledgementPdfService
{
    public function generatePdfFromHtml(string $html): string
    {
        $html2pdf = new Html2Pdf('P', 'A4', 'en');
        $html2pdf->pdf->SetTitle('Document Acknowledgement');
        $html2pdf->writeHTML($html);

        return $html2pdf->output('', 'S');
    }

    public function signPdf(string $sourcePath, string $signatureData, int $pageNumber, float $x, float $y, string $signerName, string $saveTo): string
    {
        $signaturePath = $this->buildSignatureImagePath($signatureData);
        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pageCount = $pdf->setSourceFile($sourcePath);

        for ($page = 1; $page <= $pageCount; $page++) {
            $templateId = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            if ($page === $pageNumber) {
                $pdf->Image($signaturePath, $x, $y, 50, 0, 'PNG');
                $pdf->SetFont('helvetica', 'I', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY($x, $y + 25);
                $pdf->Cell(0, 0, sprintf('Signed by %s on %s', $signerName, now()->format('d M Y H:i')));
            }
        }

        if (file_exists($signaturePath) && str_contains($signaturePath, sys_get_temp_dir())) {
            @unlink($signaturePath);
        }

        $pdf->Output($saveTo, 'F');

        return $saveTo;
    }

    protected function buildSignatureImagePath(string $signatureData): string
    {
        if (preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $signatureData)) {
            [, $imageData] = explode(',', $signatureData, 2);
            $binary = base64_decode($imageData);
            $path = tempnam(sys_get_temp_dir(), 'sig_') . '.png';
            file_put_contents($path, $binary);
            return $path;
        }

        if (Storage::disk('public')->exists($signatureData)) {
            return Storage::disk('public')->path($signatureData);
        }

        throw new \RuntimeException('Unable to resolve signature image path.');
    }
}
