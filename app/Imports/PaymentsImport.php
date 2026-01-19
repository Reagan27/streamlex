<?php

namespace Vanguard\Imports;

use Vanguard\Payment;
use Vanguard\MismatchedPayment;
use Vanguard\UserDocument;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\DB;

class PaymentsImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    private $paymentCycleId;
    private $matchedCount = 0;
    private $mismatchedCount = 0;
    private $skippedDuplicates = 0;

    public function __construct($paymentCycleId)
    {
        if (!$paymentCycleId) {
            throw new \Exception('Payment cycle ID is required');
        }
        $this->paymentCycleId = $paymentCycleId;
    }

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Clean up headers
            $row = array_combine(
                array_map(function($key) {
                    return strtolower(str_replace(' ', '_', $key));
                }, array_keys($row)),
                array_values($row)
            );

            // Check for duplicate payment
            $existingPayment = Payment::where('payment_cycle_id', $this->paymentCycleId)
                                    ->where('id_number', $row['id_number'])
                                    ->first();

            if ($existingPayment) {
                $this->skippedDuplicates++;
                DB::commit();
                return null;
            }

            // Look up user document
            $userDocument = UserDocument::where('id_number', $row['id_number'])->first();

            // Prepare payment data
            $paymentData = [
                'payment_cycle_id' => $this->paymentCycleId,
                'id_number' => $row['id_number'],
                'imported_name' => $row['name'],
                'productivity' => $row['productivity'],
                'amount_payable' => $row['total_amount'],
                'net_payable' => $row['net_payable'],
                'taxable_amount' => $row['taxable_amount'],
                'tax' => $row['tax'],
                'advance_pay' => $row['advance_pay'],
                'status' => $row['status'] ?? 'Pending',
                'invoice_type' => 'aggregated' // Set all new imports as aggregated
            ];

            if ($userDocument) {
                // Create matched payment
                $payment = new Payment(array_merge($paymentData, [
                    'user_id' => $userDocument->user_id
                ]));
                $this->matchedCount++;
                DB::commit();
                return $payment;
            } else {
                // Create mismatched payment record
                MismatchedPayment::create($paymentData);
                $this->mismatchedCount++;
                DB::commit();
                return null;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getRowCount(): int
    {
        return $this->matchedCount;
    }

    public function getSkippedRowCount(): int
    {
        return $this->mismatchedCount;
    }

    public function getSkippedDuplicatesCount(): int
    {
        return $this->skippedDuplicates;
    }
}