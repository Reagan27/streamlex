<?php

namespace Vanguard\Services;

use Exception;
use Spipu\Html2Pdf\Html2Pdf;

class NdaPdfService
{
    protected $html2pdf;

    public function __construct()
    {
        $this->html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 15, 15, 15]);
        $this->html2pdf->setDefaultFont('helvetica');
    }

    public function generateNda($html)
    {
        try {
            $this->html2pdf->writeHTML($html);
            return $this->html2pdf->output('', 'S');
        } catch (Exception $e) {
            throw new Exception('Error generating NDA PDF: ' . $e->getMessage());
        }
    }
}
