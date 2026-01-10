<?php

namespace Vanguard\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Vanguard\MismatchedPayment;

class MismatchedPaymentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MismatchedPayment::with('paymentCycle')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Imported Name',
            'ID Number',
            'Amount Payable',
            'Net Payable',
            'Taxable Amount',
            'Tax',
            'Advance Pay',
            'Productivity',
            'Payment Cycle',
            'Status',
            'Created Date'
        ];
    }

    /**
     * @param MismatchedPayment $payment
     * @return array
     */
    public function map($payment): array
    {
        return [
            $payment->imported_name,
            $payment->id_number,
            number_format($payment->amount_payable, 2),
            number_format($payment->net_payable, 2),
            number_format($payment->taxable_amount, 2),
            number_format($payment->tax, 2),
            number_format($payment->advance_pay, 2),
            number_format($payment->productivity, 1) . '%',
            $payment->paymentCycle->description ?? 'N/A',
            $payment->status ?? 'Pending',
            $payment->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as headers
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'F4F4F4'
                    ]
                ]
            ],
            
            // Apply borders to all cells
            'A1:K' . ($this->collection()->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            
            // Format number columns
            'C2:G' . ($this->collection()->count() + 1) => [
                'numberFormat' => [
                    'formatCode' => '#,##0.00'
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                ]
            ],
            
            // Format percentage column
            'H2:H' . ($this->collection()->count() + 1) => [
                'numberFormat' => [
                    'formatCode' => '#,##0.0"%"'
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                ]
            ]
        ];
    }
}