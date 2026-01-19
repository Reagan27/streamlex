<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Payment;
use Vanguard\County;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Exports\InvoicePaymentsDetailedExport;
use Vanguard\Exports\InvoicePaymentsExport;
use Vanguard\Exports\InvoicePaymentsSummaryExport;
use Vanguard\User;

class InvoicePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Create a subquery for payment totals
        $paymentTotals = Payment::select(
            'user_id',
            DB::raw('(SUM(net_payable) + SUM(tax)) as total_amount_payable'),
            DB::raw('SUM(tax) as total_tax'),
            DB::raw('SUM(net_payable) as total_net_payable'),
            DB::raw('COUNT(*) as total_payments'),
            DB::raw('COUNT(CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 1 END) as invoice_count')
        )->groupBy('user_id');

        // Start user query
        $query = User::with('county')
            ->joinSub($paymentTotals, 'payment_totals', function($join) {
                $join->on('users.id', '=', 'payment_totals.user_id');
            });

        // Apply county filter
        if ($request->filled('county')) {
            $query->where('users.county_id', $request->county);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Get counties for filter dropdown
        $counties = County::orderBy('name', 'asc')->get();

        // Calculate overall totals
        $totals = Payment::select(
            DB::raw('(SUM(net_payable) + SUM(tax)) as total_amount'),
            DB::raw('SUM(tax) as total_tax'),
            DB::raw('SUM(net_payable) as total_net'),
            DB::raw('COUNT(*) as total_payments'),
            DB::raw('COUNT(CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 1 END) as total_invoices')
        )->first();

        // Get paginated results with all required fields
        $users = $query->select([
            'users.*',
            'payment_totals.total_amount_payable',
            'payment_totals.total_tax',
            'payment_totals.total_net_payable',
            'payment_totals.total_payments',
            'payment_totals.invoice_count'
        ])
        ->orderByDesc('payment_totals.total_amount_payable')
        ->paginate(15)
        ->withQueryString();

        return view('invoice-payments.index', compact(
            'users',
            'counties',
            'totals'
        ));
    }

    public function show(Request $request, User $user)
    {
        $query = Payment::where('user_id', $user->id)
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            });
    
        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
    
        $totals = Payment::where('user_id', $user->id)
            ->select(
                DB::raw('(SUM(net_payable) + SUM(tax)) as total_amount'),
                DB::raw('SUM(tax) as total_tax'),
                DB::raw('SUM(net_payable) as total_net'),
                DB::raw('COUNT(*) as total_payments'),
                DB::raw('COUNT(CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 1 END) as total_invoices')
            )->first();
    
        return view('invoice-payments.show', compact('user', 'payments', 'totals'));
    }

    public function updateField(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:payments,id',
            'field' => 'required|in:amount_payable,tax,net_payable',
            'value' => 'required|numeric|min:0',
        ]);

        try {
            $payment = Payment::findOrFail($request->id);
            $payment->{$request->field} = $request->value;
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Field updated successfully',
                'formatted_value' => number_format($request->value, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Rejected',
            'rejection_reason' => 'required_if:status,Rejected|nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => $request->status,
                'rejection_reason' => $request->status === 'Rejected' ? $request->rejection_reason : null
            ]);
            
            DB::commit();
            return back()
                ->with('success', 'Payment status updated successfully.')
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Error updating payment status: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function export(Request $request)
    {
        // Get users with their payment totals
        $query = User::with('county')
        ->whereHas('payments')
        ->withSum('payments as total_tax', 'tax')
        ->withSum('payments as total_net_payable', 'net_payable')
        ->withCount(['payments as total_payments'])
        ->withCount(['payments as invoice_count' => function ($query) {
            $query->whereNotNull('invoice_file')
                  ->orWhereNotNull('new_invoice_file');
        }])
        ->selectRaw('users.*, (payments_total_net_payable_sum + payments_total_tax_sum) as total_amount_payable');

        // Apply county filter
        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        return Excel::download(
            new InvoicePaymentsSummaryExport($users),
            'invoice_payments_summary_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function updatePaymentStatus(Request $request, User $user)
    {
        try {
            $user->update([
                'approved_for_payment' => $request->boolean('approved_for_payment')
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Payment processing status updated successfully',
                'status' => $user->approved_for_payment
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportDetailed(Request $request, User $user)
    {
        $payments = Payment::with(['paymentCycle'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $filename = 'invoice_payments_' . 
            strtolower(str_replace(' ', '_', $user->first_name)) . '_' . 
            strtolower(str_replace(' ', '_', $user->last_name)) . '_' . 
            now()->format('Y-m-d') . '.xlsx';
    
        return Excel::download(
            new InvoicePaymentsDetailedExport($user, $payments),
            $filename
        );
    }
}