<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Payment;
use Vanguard\PaymentCycle;
use Vanguard\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\PaymentsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Vanguard\UserDocument;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $paymentCycles = PaymentCycle::orderBy('description', 'asc')->get();
        
        if ($paymentCycles->isEmpty()) {
            return view('payments.index', [
                'paymentCycles' => collect(),
                'payments' => collect(),
                'selectedCycle' => null,
                'warning' => 'No payment cycles found.'
            ]);
        }
        
        $selectedCycle = $request->input('cycle_id')
            ? PaymentCycle::findOrFail($request->input('cycle_id'))
            : $paymentCycles->first();
        
        $paymentsQuery = Payment::where('payment_cycle_id', $selectedCycle->id)
            ->with('user');
    

        if ($request->filled('search')) {
            $search = $request->input('search');
            $paymentsQuery->where(function($query) use ($search) {
                $query->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where(function($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->orWhere('id_number', 'like', "%{$search}%")
                ->orWhere('imported_name', 'like', "%{$search}%");
            });
        }
    
        if ($request->has('sort_invoices')) {
            $direction = $request->input('sort_invoices') === 'desc' ? 'desc' : 'asc';
            $paymentsQuery->orderByRaw('CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 0 ELSE 1 END ' . $direction);
        }
        
        $payments = $paymentsQuery->paginate(15)->withQueryString();
        
        $cycleTotals = Payment::where('payment_cycle_id', $selectedCycle->id)
            ->selectRaw('
                SUM(amount_payable) as total_amount, 
                SUM(tax) as total_tax,
                COUNT(CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 1 END) as total_invoices,
                COUNT(*) as total_possible_invoices
            ')
            ->first();
        
        return view('payments.index', compact('paymentCycles', 'selectedCycle', 'payments', 'cycleTotals'));
    }

    public function userPayments()
    {
        $user = auth()->user();
        
        $pagedPayments = Payment::with('paymentCycle')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('payments.user', [
            'pagedPayments' => $pagedPayments,
            'rejectedCount' => $pagedPayments->where('status', 'Rejected')->count()
        ]);
    }

    public function uploadCombinedInvoice(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    
        $user = auth()->user();
        DB::beginTransaction();
        
        try {
            $filePath = $request->file('invoice_file')->store('invoices', 'public');
            
            // Get all aggregated payments that need invoices
            $paymentsToUpdate = Payment::where('user_id', $user->id)
                ->where('invoice_type', 'aggregated')
                ->where(function($query) {
                    $query->whereNull('new_invoice_file')  // New uploads without invoice
                          ->orWhere('status', 'Rejected'); // Or rejected ones
                })
                ->get();
    
            foreach ($paymentsToUpdate as $payment) {
                $payment->update([
                    'new_invoice_number' => $request->invoice_number,
                    'new_invoice_file' => $filePath,
                    'status' => 'Invoice Uploaded',
                    'rejection_reason' => null
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Combined invoice uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($filePath ?? '');
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading invoice: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function uploadIndividualInvoice(Request $request, Payment $payment)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    
        DB::beginTransaction();
        
        try {
            $filePath = $request->file('invoice_file')->store('invoices', 'public');
            
            $payment->update([
                'invoice_number' => $request->invoice_number,
                'invoice_file' => $filePath,
                'status' => 'Invoice Uploaded',
                'rejection_reason' => null
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($filePath ?? '');
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading invoice: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Approved,Rejected',
            'rejection_reason' => 'required_if:status,Rejected|nullable|string|max:1000',
        ]);
    
        DB::beginTransaction();
        try {
            $updateData = ['status' => $request->status];
    
            if ($request->status === 'Rejected') {
                $updateData['rejection_reason'] = $request->rejection_reason;
                
                if ($payment->isIndividualInvoice()) {
                    // Old implementation - update only the specific payment cycle
                    Payment::where('user_id', $payment->user_id)
                        ->where('payment_cycle_id', $payment->payment_cycle_id)
                        ->where('invoice_type', 'individual')
                        ->update([
                            'status' => 'Rejected',
                            'rejection_reason' => $request->rejection_reason
                        ]);
                } else {
                    // New implementation - update all aggregated payments
                    Payment::where('user_id', $payment->user_id)
                        ->where('new_invoice_file', $payment->new_invoice_file)
                        ->where('invoice_type', 'aggregated')
                        ->update([
                            'status' => 'Rejected',
                            'rejection_reason' => $request->rejection_reason
                        ]);
                }
            } else {
                $updateData['rejection_reason'] = null;
                
                if ($payment->isIndividualInvoice()) {
                    // Old implementation - update only the specific payment cycle
                    Payment::where('user_id', $payment->user_id)
                        ->where('payment_cycle_id', $payment->payment_cycle_id)
                        ->where('invoice_type', 'individual')
                        ->update([
                            'status' => $request->status,
                            'rejection_reason' => null
                        ]);
                } else {
                    // New implementation - update all aggregated payments
                    Payment::where('user_id', $payment->user_id)
                        ->where('new_invoice_file', $payment->new_invoice_file)
                        ->where('invoice_type', 'aggregated')
                        ->update([
                            'status' => $request->status,
                            'rejection_reason' => null
                        ]);
                }
            }
    
            DB::commit();
            return back()->with('success', 'Payment status updated successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating payment status: ' . $e->getMessage());
        }
    }



public function editCycle(Request $request, PaymentCycle $cycle)
{
    $request->validate([
        'description' => 'required|string|max:255',
        'is_invoicable' => 'required|boolean'
    ]);

    $cycle->update([
        'description' => $request->description,
        'is_invoicable' => $request->boolean('is_invoicable')
    ]);

    return redirect()->route('payments.index')
        ->with('success', 'Payment cycle updated successfully');
}



public function import()
{
    $paymentCycles = PaymentCycle::orderBy('start_date', 'desc')->get();
    return view('payments.import', compact('paymentCycles'));
}

public function processImport(Request $request)
{
    try {
        // First validate the file and cycle option
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'cycle_option' => 'required|in:existing,new',
        ]);

        // Then validate based on cycle option
        if ($request->cycle_option === 'existing') {
            $request->validate([
                'payment_cycle' => 'required|exists:payment_cycles,id'
            ]);
            $cycleId = $request->payment_cycle;
        } else {
            $request->validate([
                'cycle_description' => 'required|string|max:255'
            ]);
            
            // Create new payment cycle
            $paymentCycle = PaymentCycle::create([
                'description' => $request->cycle_description,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'is_invoicable' => $request->boolean('is_invoicable'),
            ]);
            $cycleId = $paymentCycle->id;
        }

        DB::beginTransaction();

        try {
            // Create instance of import class
            $import = new PaymentsImport($cycleId);

            // Import the file
            Excel::import($import, $request->file('file'));

            DB::commit();

            return redirect()
                ->route('payments.index')
                ->with('success', sprintf(
                    "Successfully imported %d payments. %d rows were skipped and saved to the mismatched table.",
                    $import->getRowCount(),
                    $import->getSkippedRowCount()
                ));

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->with('error', 'Import failed: ' . $e->getMessage())
            ->withInput();
    }
}

public function updateField(Request $request)
{
    $request->validate([
        'id' => 'required|exists:payments,id',
        'field' => 'required|in:amount_payable,net_payable,tax,productivity',
        'value' => 'required|numeric',
    ]);

    $payment = Payment::findOrFail($request->id);
    $payment->{$request->field} = $request->value;
    $payment->save();

    return response()->json(['success' => true]);
}

public function downloadTemplate()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'Name',
        'ID Number', 
        'Productivity',
        'Total Amount', 
        'Taxable Amount',
        'Tax',
        'Advance Pay',
        'Net Payable',
        'Status'
    ];
    $sheet->fromArray($headers, NULL, 'A1');


    $exampleData = [
        ['John Doe', '1234567', '96', '130000', '120000', '5500', '0', '130000', 'Pending'],
        ['Jane Doe', '8901234', '94.5', '125000', '115000', '4500', '10000', '125000', 'Pending']
    ];
    $sheet->fromArray($exampleData, NULL, 'A2');

    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->getStyle('A1:I1')->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E9ECEF']
        ]
    ]);

    $writer = new Xlsx($spreadsheet);
    $fileName = 'payment_import_template.xlsx';
    
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}


    public function destroy(Payment $payment)
{
    $payment->delete();
    return back()->with('success', 'Payment record deleted successfully');
}

public function showMismatched()
{
    $mismatchedPayments = Payment::whereNull('user_id')
        ->where('payment_cycle_id', '!=', null) // Ensure it's from an import
        ->where('id_number', '!=', null)  // Has an ID number that didn't match
        ->with('paymentCycle')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
    // dd('Found Mismatched:', $mismatchedPayments->toArray());
    
    return view('payments.mismatched', compact('mismatchedPayments'));
}

public function show(Payment $payment)
{
    $payment->load(['paymentCycle', 'user']);
    return view('payments.show', compact('payment'));
}

public function update(Request $request, Payment $payment)
{
    $request->validate([
        'id_number' => 'required|string',
        'amount_payable' => 'required|numeric',
        'tax' => 'required|numeric',
        'productivity' => 'required|numeric|min:0|max:100',
    ]);

    // First check if the ID number exists in user_documents
    $userDocument = UserDocument::where('id_number', $request->id_number)->first();

    // Begin transaction
    DB::beginTransaction();
    try {
        if ($userDocument) {
            // If ID found, update payment with user_id and other details
            $payment->update([
                'user_id' => $userDocument->user_id,
                'amount_payable' => $request->amount_payable,
                'tax' => $request->tax,
                'productivity' => $request->productivity
            ]);

            $message = 'Payment updated and matched with user successfully.';
        } else {
            // If ID not found, just update other fields
            $payment->update([
                'amount_payable' => $request->amount_payable,
                'tax' => $request->tax,
                'productivity' => $request->productivity
            ]);

            $message = 'Payment updated but ID number not found in system.';
        }

        DB::commit();
        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Error updating payment: ' . $e->getMessage())
            ->withInput();
    }
}

public function destroyCycle(PaymentCycle $cycle)
{
    $cycle->payments()->delete();
    $cycle->delete();
    return redirect()->route('payments.index')->with('success', 'Payment cycle and all associated payments deleted successfully');
}
}

