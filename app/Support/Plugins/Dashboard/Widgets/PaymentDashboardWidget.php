<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Vanguard\Plugins\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Vanguard\Payment;
use Vanguard\PaymentCycle;

class PaymentDashboardWidget extends Widget 
{
    public ?string $width = '12';
    
    protected string|\Closure|array $permissions = 'payments.dashboard';
    
    public function render(): View 
    {
        try {
            $currentUser = auth()->user();
            $isAdmin = $currentUser->isAdmin();
            $isManager = $currentUser->hasRole('Manager');
            $isFinance = $currentUser->hasRole('Finance');
            $hasAdminAccess = $isAdmin || $isManager || $isFinance;
            
            $cycles = PaymentCycle::orderBy('start_date', 'desc')->get();
            $currentCycle = $cycles->first();

            if ($hasAdminAccess) {
                $stats = $this->getAdminStats($currentCycle);
                $allTimeStats = $this->getAdminAllTimeStats();
                $pendingInvoicePayments = null;
            } else {
                $stats = $this->getUserStats($currentUser, $currentCycle);
                $allTimeStats = $this->getUserAllTimeStats($currentUser);
                $pendingInvoicePayments = $this->getPendingInvoicePayments($currentUser);
            }
            
            return view('plugins.dashboard.widgets.payment-dashboard', [
                'stats' => $stats,
                'allTimeStats' => $allTimeStats,
                'cycles' => $cycles,
                'isAdmin' => $isAdmin,
                'isManager' => $isManager,
                'isFinance' => $isFinance,
                'hasAdminAccess' => $hasAdminAccess,
                'currentCycle' => $currentCycle,
                'pendingInvoicePayments' => $pendingInvoicePayments
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getUserStats($user, $currentCycle)
    {
        $query = Payment::query()
            ->where('user_id', $user->id)
            ->select([
                DB::raw('SUM(net_payable) as total_net_payable'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN net_payable ELSE 0 END) as net_paid'),
                DB::raw('SUM(CASE WHEN status = "Pending" OR status = "Processing" THEN net_payable ELSE 0 END) as pending_amount'), // Modified
                DB::raw('SUM(tax) as total_tax'),
                DB::raw('SUM(advance_pay) as total_advance'),
                DB::raw('AVG(productivity) as avg_productivity'),
                DB::raw('COUNT(*) as total_payments'),
                DB::raw('COUNT(CASE WHEN status = "Approved" THEN 1 END) as approved_payments'),
                DB::raw('COUNT(CASE WHEN status = "Pending" OR status = "Processing" THEN 1 END) as pending_count') // Added
            ]);
    
        if ($currentCycle) {
            $query->where('payment_cycle_id', $currentCycle->id);
        }
    
        return $query->first();
    }
    
    private function getUserAllTimeStats($user)
    {
        return Payment::query()
            ->where('user_id', $user->id)
            ->select([
                DB::raw('SUM(net_payable) as total_all_time_earnings'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN net_payable ELSE 0 END) as total_all_time_paid'),
                DB::raw('SUM(CASE WHEN status = "Pending" OR status = "Processing" THEN net_payable ELSE 0 END) as total_pending_amount'), // Added
                DB::raw('SUM(tax) as total_all_time_tax'),
                DB::raw('SUM(advance_pay) as total_all_time_advance'),
                DB::raw('COUNT(DISTINCT payment_cycle_id) as participated_cycles'),
                DB::raw('AVG(productivity) as overall_avg_productivity'),
                DB::raw('MAX(net_payable) as highest_payment'),
                DB::raw('MIN(net_payable) as lowest_payment'),
                DB::raw('COUNT(CASE WHEN status = "Pending" OR status = "Processing" THEN 1 END) as pending_payment_count') // Added
            ])
            ->first();
    }
    
    private function getAdminStats($currentCycle)
    {
        $query = Payment::query()
            ->select([
                DB::raw('SUM(net_payable) as total_net_payable'),               // Changed to ensure correct total
                DB::raw('SUM(CASE WHEN status = "Approved" THEN net_payable ELSE 0 END) as net_paid'),
                DB::raw('SUM(tax) as total_tax'),
                DB::raw('SUM(advance_pay) as total_advance'),
                DB::raw('AVG(productivity) as avg_productivity'),
                DB::raw('COUNT(CASE WHEN invoice_file IS NOT NULL THEN 1 END) as invoice_count'),
                DB::raw('COUNT(CASE WHEN status = "Approved" THEN 1 END) as paid_count'),
                DB::raw('SUM(CASE WHEN status != "Approved" THEN net_payable ELSE 0 END) as pending_amount'),
                DB::raw('COUNT(CASE WHEN status != "Approved" THEN 1 END) as pending_count')
            ]);
    
        if ($currentCycle) {
            $query->where('payment_cycle_id', $currentCycle->id);
        }
    
        return $query->first();
    }

    private function getPendingInvoicePayments($user)
    {
       
        $payments = Payment::where('user_id', $user->id)
        ->where(function($query) {
            $query->where(function($q) {
                // Check both old and new invoice files
                $q->whereNull('invoice_file')
                  ->whereNull('new_invoice_file');
            })
            ->orWhere('status', 'Rejected');
        })
        ->whereHas('paymentCycle', function($query) {
            $query->where('is_invoicable', true);
        })
        ->with('paymentCycle')
        ->orderBy('payment_cycle_id', 'desc')
        ->get();

    $groupedPayments = $payments->groupBy('payment_cycle_id')
        ->map(function($cyclePayments) {
            return (object)[
                'cycle_description' => $cyclePayments->first()->paymentCycle->description,
                'payments' => $cyclePayments->map(function($payment) {
                    return (object)[
                        'id' => $payment->id,
                        'amount_payable' => $payment->amount_payable,
                        'net_payable' => $payment->net_payable,
                        'tax' => $payment->tax,
                        'advance_pay' => $payment->advance_pay,
                        'status' => $payment->status,
                        'rejection_reason' => $payment->rejection_reason,
                        'invoice_file' => $payment->invoice_file,
                        'new_invoice_file' => $payment->new_invoice_file
                    ];
                }),
                'total_amount' => $cyclePayments->sum('amount_payable'),
                'total_net_payable' => $cyclePayments->sum('net_payable'),
                'total_tax' => $cyclePayments->sum('tax'),
                'total_advance' => $cyclePayments->sum('advance_pay'),
                'has_rejected' => $cyclePayments->contains('status', 'Rejected')
            ];
        });
    
        $grandTotals = (object)[
            'total_amount' => $payments->sum('amount_payable'),
            'total_net_payable' => $payments->sum('net_payable'),  // Added this line
            'total_tax' => $payments->sum('tax'),
            'total_advance' => $payments->sum('advance_pay'),
            'total_payments' => $payments->count()
        ];
    
        return [
            'cycles' => $groupedPayments,
            'totals' => $grandTotals
        ];
    }



private function getAdminAllTimeStats()
{
    return Payment::query()
        ->select([
            DB::raw('SUM(net_payable) as total_all_time_payable'),
            DB::raw('SUM(CASE WHEN status = "Approved" THEN net_payable ELSE 0 END) as total_paid_amount'),
            DB::raw('COUNT(CASE WHEN status = "Approved" THEN 1 END) as total_paid_count'),
            DB::raw('COUNT(*) as total_possible_invoices'),
            DB::raw('SUM(CASE WHEN status != "Approved" THEN net_payable ELSE 0 END) as total_pending_amount'),
            DB::raw('COUNT(CASE WHEN status != "Approved" THEN 1 END) as total_pending_count'),
            DB::raw('COUNT(CASE WHEN invoice_file IS NOT NULL OR new_invoice_file IS NOT NULL THEN 1 END) as total_invoices'),
            DB::raw('COUNT(DISTINCT user_id) as total_users'),
            DB::raw('AVG(productivity) as overall_avg_productivity'),
            DB::raw('SUM(tax) as total_tax'),
            DB::raw('SUM(advance_pay) as total_advance'),
            DB::raw('COUNT(DISTINCT payment_cycle_id) as total_cycles')
        ])
        ->first();
}
}