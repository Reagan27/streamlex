<?php

namespace Vanguard\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InvoicePaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Email',
            'Phone Number',
            'County',
            'ID Number',
            'Total Amount Payable',
            'Total Tax',
            'Total Advance Pay',
            'Total Net Payable',
            'Invoice Number',
            'Invoice Status',
            'Status'
        ];
    }

    public function map($payment): array
    {
        $user = $payment->user;

        return [
            $user ? ($user->first_name . ' ' . $user->last_name) : 'N/A',
            $user ? $user->email : 'N/A',
            $user ? $user->phone : 'N/A',
            $user && $user->county ? $user->county->name : 'N/A',
            $payment->id_number ?? 'N/A',
            number_format($payment->amount_payable, 2),
            number_format($payment->tax, 2),
            number_format($payment->advance_pay ?? 0, 2),
            number_format($payment->net_payable, 2),
            $payment->invoice_number ?? 'N/A',
            $payment->invoice_file || $payment->new_invoice_file ? 'Submitted' : 'Pending',
            $payment->status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4A90E2']
            ]
        ]);

        // Add borders to all cells
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Center align specific columns
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->getAlignment()->setVertical('center');
        
        // Right align amount columns
        $sheet->getStyle('F1:I' . $lastRow)->getAlignment()->setHorizontal('right');

        // Wrap text in all cells
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->getAlignment()->setWrapText(true);

        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}