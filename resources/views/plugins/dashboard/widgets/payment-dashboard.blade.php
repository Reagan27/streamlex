@section('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
        height: calc(100% - 1.5rem);
    }
    
    .dashboard-card {
        height: 100%;
        transition: transform 0.2s;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
    }
    
    .stat-icon {
        font-size: 1.5rem;
        opacity: 0.8;
    }
    
    .stats-divider {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin: 1.5rem 0;
    }

    .section-title {
        font-size: 1.2rem;
        color: #495057;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .main-card {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-invoice {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .invoice-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .invoice-required {
        background-color: #dc3545;
    }

    .invoice-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .widget-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .widget-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .row {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    .col-md-3, .col-md-4, .col-md-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .payment-detail {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .payment-label {
        color: #6c757d;
    }

    .payment-value {
        font-weight: 500;
    }
</style>
@endsection

<div class="widget-container">
    <div class="widget-content">
        @if(!$hasAdminAccess)
            <div class="main-card">
                <h6 class="section-title">
                    <i class="fas fa-chart-line mr-2"></i>My Statistics
                </h6>

                @if($pendingInvoicePayments && count($pendingInvoicePayments['cycles']) > 0)
 <div class="alert-invoice">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Invoice Required</strong>
        </div>
        <div class="text-right">
        <div class="font-weight-bold">
    Invoice Amount: KES {{ number_format($pendingInvoicePayments['totals']->total_net_payable + $pendingInvoicePayments['totals']->total_tax, 2) }}
</div>
            <small class="text-muted">
                 Tax: KES {{ number_format($pendingInvoicePayments['totals']->total_tax, 2) }} |
                 Advance: KES {{ number_format($pendingInvoicePayments['totals']->total_advance, 2) }}
            </small>
        </div>
    </div>
    
    <div class="invoice-list">
        @foreach($pendingInvoicePayments['cycles'] as $cycle)
            <div class="invoice-status mb-3">
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $cycle->cycle_description }}</span>
                        <span class="font-weight-bold">Net: KES {{ number_format($cycle->total_net_payable, 2) }}</span>
                    </div>
                    <small class="text-muted">
                        Tax: KES {{ number_format($cycle->total_tax, 2) }} |
                        Advance: KES {{ number_format($cycle->total_advance, 2) }}
                    </small>
                    @if($cycle->has_rejected)
                        @foreach($cycle->payments as $payment)
                            @if($payment->status === 'Rejected' && $payment->rejection_reason)
                                <small class="text-danger mt-1">
                                    Rejection Reason: {{ $payment->rejection_reason }}
                                </small>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

                <div class="row">
<!-- Total Amount -->
<div class="col-md-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="card-title mb-0 font-weight-bold">Total Amount</h6>
            <i class="fas fa-money-bill-wave stat-icon text-success"></i>
        </div>
        <div class="card-body">
            <div class="payment-detail mb-3">
                <span class="payment-label">Total Net Payable:</span>
                <span class="payment-value font-weight-bold">KES {{ number_format($allTimeStats->total_all_time_earnings, 2) }}</span>
            </div>
            <div class="payment-detail">
                <span class="payment-label">Advance:</span>
                <span class="payment-value">KES {{ number_format($allTimeStats->total_all_time_advance, 2) }}</span>
            </div>
            <small class="text-muted d-block mt-2">From {{ $allTimeStats->participated_cycles }} Payment Cycles</small>
        </div>
    </div>
</div>

<!-- Payment Status -->
<div class="col-md-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="card-title mb-0 font-weight-bold">Payment Status</h6>
            <i class="fas fa-check-circle stat-icon text-info"></i>
        </div>
        <div class="card-body">
            <div class="payment-detail">
                <span class="payment-label">Net Paid:</span>
                <span class="payment-value text-success">KES {{ number_format($allTimeStats->total_all_time_paid, 2) }}</span>
            </div>
            <div class="payment-detail mb-3">
                <span class="payment-label">Pending Payments:</span>
                <span class="payment-value text-warning">KES {{ number_format($allTimeStats->total_pending_amount, 2) }}</span>
                <small class="text-muted">({{ $allTimeStats->pending_payment_count }} payments)</small>
            </div>
            @php
                $paidPercentage = $allTimeStats->total_all_time_earnings > 0 
                    ? ($allTimeStats->total_all_time_paid / $allTimeStats->total_all_time_earnings) * 100 
                    : 0;
            @endphp
            <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: {{ $paidPercentage }}%"></div>
            </div>
        </div>
    </div>
</div>

                    <!-- Tax and Deductions -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Tax & Deductions</h6>
                                <i class="fas fa-receipt stat-icon text-warning"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->total_all_time_tax, 2) }}</h5>
                                <div class="payment-detail">
                                    <span class="payment-label">Current Tax:</span>
                                    <span class="payment-value">{{ number_format($stats->total_tax, 2) }}</span>
                                </div>
                                <div class="payment-detail">
                                    <span class="payment-label">Current Advance:</span>
                                    <span class="payment-value">{{ number_format($stats->total_advance, 2) }}</span>
                                </div>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Overall Performance -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Overall Productivity</h6>
                                <i class="fas fa-chart-line stat-icon text-primary"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->overall_avg_productivity, 1) }}</h5>
                                <small class="text-muted">Average Productivity Rate</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $allTimeStats->overall_avg_productivity }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Range -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Payment Range</h6>
                                <i class="fas fa-chart-bar stat-icon text-info"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Highest</small>
                                        <h4 class="mb-0">{{ number_format($allTimeStats->highest_payment, 2) }}</h4>
                                    </div>
                                    <div class="text-right">
                                        <small class="text-muted d-block">Lowest</small>
                                        <h4 class="mb-0">{{ number_format($allTimeStats->lowest_payment, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="main-card">
                <h6 class="section-title">
                    <i class="fas fa-chart-line mr-2"></i>Overall Statistics
                </h6>
                
                <div class="row">
                    <!-- Total Amount -->
                    <div class="col-md-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="card-title mb-0 font-weight-bold">Total Amount</h6>
            <i class="fas fa-money-bill-wave stat-icon text-success"></i>
        </div>
        <div class="card-body">
            <h5 class="mb-2">{{ number_format($allTimeStats->total_all_time_payable, 2) }}</h5>
            <div class="payment-detail">
                <span class="payment-label">Net Paid:</span>
                <span class="payment-value">{{ number_format($allTimeStats->total_paid_amount, 2) }}</span>
            </div>
            <small class="text-muted">Across {{ $allTimeStats->total_cycles }} Payment Cycles</small>
        </div>
    </div>
</div>

                    <!-- Deductions -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Deductions</h6>
                                <i class="fas fa-calculator stat-icon text-primary"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->total_tax + $allTimeStats->total_advance, 2) }}</h5>
                                <div class="payment-detail">
                                    <span class="payment-label">Tax:</span>
                                    <span class="payment-value">{{ number_format($allTimeStats->total_tax, 2) }}</span>
                                </div>
                                <div class="payment-detail">
                                    <span class="payment-label">Advance:</span>
                                    <span class="payment-value">{{ number_format($allTimeStats->total_advance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Pending</h6>
                                <i class="fas fa-clock stat-icon text-warning"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->total_pending_amount, 2) }}</h5>
                                <small class="text-muted">{{ $allTimeStats->total_pending_count }} Payments Pending</small>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Invoices</h6>
                                <i class="fas fa-file-invoice stat-icon text-info"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->total_invoices) }}</h5>
                                <small class="text-muted">Invoices Uploaded</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- System Users -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">System Users</h6>
                                <i class="fas fa-users stat-icon text-success"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->total_users) }}</h5>
                                <small class="text-muted">Registered Payment Recipients</small>
                            </div>
                        </div>
                    </div>

                    <!-- Cycle Performance -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Overall Performance</h6>
                                <i class="fas fa-chart-line stat-icon text-primary"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2">{{ number_format($allTimeStats->overall_avg_productivity, 1) }}</h5>
                                <small class="text-muted">Average Productivity Rate</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $allTimeStats->overall_avg_productivity }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Cycle -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h6 class="card-title mb-0 font-weight-bold">Current Cycle</h6>
                                <i class="fas fa-calendar-alt stat-icon text-info"></i>
                            </div>
                            <div class="card-body">
                                @if($currentCycle)
                                    <h5 class="mb-2">{{ $currentCycle->description }}</h5>
                                    <div class="payment-detail">
                                        <span class="payment-label">Total:</span>
                                        <span class="payment-value">{{ number_format($stats->total_amount, 2) }}</span>
                                    </div>
                                    <div class="payment-detail">
                                        <span class="payment-label">Paid:</span>
                                        <span class="payment-value">{{ number_format($stats->net_paid, 2) }}</span>
                                    </div>
                                    <div class="payment-detail">
                                        <span class="payment-label">Pending:</span>
                                        <span class="payment-value">{{ number_format($stats->pending_amount, 2) }}</span>
                                    </div>
                                @else
                                    <div class="text-muted">No active payment cycle</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($isAdmin || $isManager)
                    <div class="row mt-4">
                        <!-- Payment Distribution -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center py-3">
                                    <h6 class="card-title mb-0 font-weight-bold">Payment Distribution</h6>
                                    <i class="fas fa-chart-pie stat-icon text-primary"></i>
                                </div>
                                <div class="card-body">
                                    <div class="payment-detail">
                                        <span class="payment-label">Approved Payments:</span>
                                        <span class="payment-value">{{ number_format($allTimeStats->total_paid_count) }}</span>
                                    </div>
                                    <div class="payment-detail">
                                        <span class="payment-label">Pending Payments:</span>
                                        <span class="payment-value">{{ number_format($allTimeStats->total_pending_count) }}</span>
                                    </div>
                                    <div class="payment-detail">
                                        <span class="payment-label">Total Tax Deducted:</span>
                                        <span class="payment-value">{{ number_format($allTimeStats->total_tax, 2) }}</span>
                                    </div>
                                    <div class="payment-detail mt-3">
                                        <span class="payment-label">Completion Rate:</span>
                                        <span class="payment-value">
                                            @php
                                                $totalPayments = $allTimeStats->total_paid_count + $allTimeStats->total_pending_count;
                                                $completionRate = $totalPayments > 0 
                                                    ? ($allTimeStats->total_paid_count / $totalPayments) * 100 
                                                    : 0;
                                            @endphp
                                            {{ number_format($completionRate, 1) }}%
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Health -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center py-3">
                                    <h6 class="card-title mb-0 font-weight-bold">System Overview</h6>
                                    <i class="fas fa-heartbeat stat-icon text-danger"></i>
                                </div>
                                <div class="card-body">
                                    <div class="payment-detail">
                                        <span class="payment-label">Total Cycles:</span>
                                        <span class="payment-value">{{ number_format($allTimeStats->total_cycles) }}</span>
                                    </div>
                                    <div class="payment-detail">
                                        <span class="payment-label">Active Users:</span>
                                        <span class="payment-value">{{ number_format($allTimeStats->total_users) }}</span>
                                    </div>
                                    <div class="payment-detail">
    <span class="payment-label">Invoice Compliance:</span>
    <span class="payment-value">
        @php
            $invoiceCompliance = $allTimeStats->total_possible_invoices > 0
                ? ($allTimeStats->total_invoices / $allTimeStats->total_possible_invoices) * 100
                : 0;
        @endphp
        {{ number_format($invoiceCompliance, 1) }}%
        <small class="text-muted">({{ $allTimeStats->total_invoices }}/{{ $allTimeStats->total_possible_invoices }})</small>
    </span>
</div>
                                    <div class="progress mt-3" style="height: 4px;">
                                        <div class="progress-bar bg-info" style="width: {{ $invoiceCompliance }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>