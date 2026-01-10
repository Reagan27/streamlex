<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\MismatchedPayment;
use Vanguard\Payment;
use Vanguard\UserDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Exports\MismatchedPaymentsExport;

class MismatchedPaymentController extends Controller
{
    public function index()
    {
        $mismatchedPayments = MismatchedPayment::with('paymentCycle')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('payments.mismatched.index', compact('mismatchedPayments'));
    }

    public function export()
    {
        return Excel::download(
            new MismatchedPaymentsExport,
            'mismatched-payments-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function update(Request $request, MismatchedPayment $payment)
    {
        $request->validate([
            'id_number' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate net_payable
            $net_payable = $payment->amount_payable - ($payment->tax + $payment->advance_pay);

            // Check if ID exists in user documents
            $userDocument = UserDocument::where('id_number', $request->id_number)->first();

            if ($userDocument) {
                // If ID found, create new payment and delete mismatched
                Payment::create([
                    'user_id' => $userDocument->user_id,
                    'payment_cycle_id' => $payment->payment_cycle_id,
                    'id_number' => $request->id_number,
                    'imported_name' => $payment->imported_name,
                    'productivity' => $payment->productivity,
                    'amount_payable' => $payment->amount_payable,
                    'net_payable' => $net_payable,
                    'status' => $payment->status,
                    'tax' => $payment->tax,
                    'advance_pay' => $payment->advance_pay,
                ]);

                $payment->delete();
                
                DB::commit();
                return response()->json([
                    'success' => true, 
                    'message' => 'Payment matched and transferred successfully'
                ]);
            }

            // If no match found, just update the ID and calculated net_payable
            $payment->update([
                'id_number' => $request->id_number,
                'net_payable' => $net_payable
            ]);

            DB::commit();
            return response()->json([
                'success' => false, 
                'message' => 'Payment updated but no matching user found'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error updating payment: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(MismatchedPayment $payment)
    {
        $payment->delete();
        return redirect()->back()->with('success', 'Payment record deleted successfully');
    }
}