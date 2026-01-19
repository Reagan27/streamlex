<?php

namespace Vanguard\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class InvoicePaymentsDetailedExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $user;
    protected $payments;
    protected $invoiceCount;
    protected $invoiceFields = [
        'Number' => 'invoice_number',
        'Status' => 'status',
        'Amount' => 'amount_payable',
        'Tax' => 'tax',
        'Advance' => 'advance_pay',
        'Net' => 'net_payable',
        'Productivity' => 'productivity',
        'Date' => 'created_at',
        'Cycle' => 'payment_cycle'
    ];

    public function __construct($user, $payments)
    {
        $this->user = $user;
        $this->payments = $payments;
        $this->invoiceCount = min($payments->count(), 5); // Limit to 5 invoices
    }

    public function collection()
    {
        return new Collection([$this->user]);
    }

    public function headings(): array
    {
        // Base headers for user info
        $headers = [
            'User Name',
            'Email',
            'Phone',
            'County',
            'ID Number',
            'Total Amount Payable',
            'Total Tax',
            'Total Advance Pay',
            'Total Net Payable'
        ];

        // Add headers for each invoice dynamically
        for ($i = 1; $i <= $this->invoiceCount; $i++) {
            foreach ($this->invoiceFields as $label => $field) {
                $headers[] = "Invoice $i $label";
            }
        }

        return $headers;
    }

    public function map($user): array
    {
        // Calculate totals
        $totalAmount = $this->payments->sum('amount_payable');
        $totalTax = $this->payments->sum('tax');
        $totalAdvance = $this->payments->sum('advance_pay');
        $totalNet = $this->payments->sum('net_payable');
        
        // Base user data
        $data = [
            $user->first_name . ' ' . $user->last_name,
            $user->email,
            $user->phone ?? 'N/A',
            $user->county->name ?? 'N/A',
            $user->id_number ?? 'N/A',
            $totalAmount,
            $totalTax,
            $totalAdvance,
            $totalNet
        ];

        // Add invoice details dynamically
        foreach ($this->payments->take($this->invoiceCount) as $payment) {
            foreach ($this->invoiceFields as $field) {
                switch ($field) {
                    case 'created_at':
                        $data[] = $payment->created_at ? $payment->created_at->format('Y-m-d') : 'N/A';
                        break;
                    case 'payment_cycle':
                        $data[] = $payment->paymentCycle ? $payment->paymentCycle->description : 'N/A';
                        break;
                    case 'amount_payable':
                    case 'tax':
                    case 'advance_pay':
                    case 'net_payable':
                        $data[] = number_format($payment->{$field} ?? 0, 2);
                        break;
                    default:
                        $data[] = $payment->{$field} ?? 'N/A';
                }
            }
        }

        // Fill remaining invoice slots if needed
        $remainingSlots = $this->invoiceCount - $this->payments->count();
        for ($i = 0; $i < $remainingSlots; $i++) {
            foreach ($this->invoiceFields as $field) {
                $data[] = 'N/A';
            }
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

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
            ]
        ];

        // Format amount columns
        $amountColumns = ['F', 'G', 'H', 'I']; // Base total amount columns
        foreach ($amountColumns as $col) {
            $styles[$col.'2:'.$col.$lastRow] = [
                'numberFormat' => ['formatCode' => '#,##0.00']
            ];
        }

        // Format each invoice's amount columns
        $baseCol = 9; // Start after the base columns
        for ($i = 0; $i < $this->invoiceCount; $i++) {
            $amountCols = range($baseCol + 3, $baseCol + 6); // Amount, Tax, Advance, Net columns
            foreach ($amountCols as $col) {
                $colLetter = $this->getColumnLetter($col);
                $styles[$colLetter.'2:'.$colLetter.$lastRow] = [
                    'numberFormat' => ['formatCode' => '#,##0.00']
                ];
            }
            $baseCol += count($this->invoiceFields);
        }

        return $styles;
    }

    protected function getColumnLetter($number)
    {
        $letter = '';
        while ($number > 0) {
            $temp = ($number - 1) % 26;
            $letter = chr(65 + $temp) . $letter;
            $number = (int)(($number - $temp) / 26);
        }
        return $letter;
    }
}