<?php

namespace Vanguard\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InvoicePaymentsSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Email',
            'Phone',
            'County',
            'Total Amount Payable',
            'Total Tax',
            'Total Net Payable',
            'Total Invoices',
            'Total Payments'
        ];
    }

    public function map($user): array
    {
        return [
            $user->first_name . ' ' . $user->last_name,
            $user->email,
            $user->phone ?? 'N/A',
            $user->county->name ?? 'N/A',
            $user->total_amount_payable ?? 0,
            $user->total_tax ?? 0,
            $user->total_net_payable ?? 0,
            $user->invoice_count ?? 0,
            $user->total_payments ?? 0
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $styles = [
            // Header styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ]
            ],
            
            // Amount columns styling
            'E2:H' . $lastRow => [
                'numberFormat' => [
                    'formatCode' => '#,##0.00'
                ]
            ]
        ];
    
        // Add green background for approved users
        $row = 2;
        foreach ($this->users as $user) {
            if ($user->approved_for_payment) {
                $styles['A'.$row.':I'.$row] = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '32CD32'] // Light green
                    ]
                ];
            }
            $row++;
        }
    
        return $styles;
    }
}