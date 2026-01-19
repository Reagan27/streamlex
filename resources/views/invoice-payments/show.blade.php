@extends('layouts.app')

@section('page-title', __('Payment Details'))
@section('page-heading', __('Payment Details for ' . $user->first_name . ' ' . $user->last_name))

@section('styles')
<style>
    .totals-box {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .total-item {
        display: inline-block;
        margin-right: 2rem;
        padding: 0.5rem;
        border-right: 1px solid #dee2e6;
    }
    .total-item:last-child {
        border-right: none;
    }
    .total-label {
        font-weight: bold;
        color: #495057;
        display: block;
        font-size: 0.9rem;
    }
    .total-value {
        color: #28a745;
        font-size: 1.1rem;
        font-weight: 500;
    }
    .editable {
        border-bottom: 1px dashed #007bff;
        cursor: pointer;
        padding: 2px 5px;
        transition: all 0.2s;
    }
    .editable:hover {
        background-color: #f8f9fa;
        border-color: #0056b3;
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #212529;
    }
    .loading {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(0, 123, 255, 0.25);
        border-top-color: #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #28a745;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('invoice-payments.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <div class="d-flex gap-2">
    <a href="{{ route('invoice-payments.export-detailed', $user->id) }}" 
       class="btn btn-success me-2">
        <i class="fas fa-file-excel"></i> Export Detailed Report
    </a>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>
</div>
    </div>

    <div class="card-body">
        <!-- User Info -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="info-card">
                    <h5 class="mb-3">User Information</h5>
                    <div class="row mb-2">
                        <div class="col-4">
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $user->first_name }} {{ $user->last_name }}</div>
                        </div>
                        <div class="col-4">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="col-4">
                            <div class="info-label">County</div>
                            <div class="info-value">{{ $user->county->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $user->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="col-4">
                            <div class="info-label">ID Number</div>
                            <div class="info-value">{{ $user->id_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-4">
    <div class="info-label">Payment Processing</div>
    <div class="info-value">
        <div class="d-flex align-items-center">
            <label class="toggle-switch mr-3 mb-0">
                <input type="checkbox" 
                       id="paymentApproval" 
                       data-user-id="{{ $user->id }}"
                       {{ $user->approved_for_payment ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
            <span class="status-label">{{ $user->approved_for_payment ? 'Approved for Payment' : 'Not Approved' }}</span>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Box -->
        <div class="totals-box">
            <div class="total-item">
                <span class="total-label">Total Amount</span>
                <span class="total-value">KES {{ number_format($totals->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="total-item">
                <span class="total-label">Total Tax</span>
                <span class="total-value">KES {{ number_format($totals->total_tax ?? 0, 2) }}</span>
            </div>
            <div class="total-item">
                <span class="total-label">Total Net</span>
                <span class="total-value">KES {{ number_format($totals->total_net ?? 0, 2) }}</span>
            </div>
            <div class="total-item">
                <span class="total-label">Invoices</span>
                <span class="total-value">{{ $totals->total_invoices }}/{{ $totals->total_payments }}</span>
            </div>
        </div>

        <!-- Status Filter -->
        <div class="mb-3">
            <form method="GET" action="{{ route('invoice-payments.show', $user->id) }}" class="row g-2">
                <div class="col-auto">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                @if(request()->has('status'))
                    <div class="col-auto">
                        <a href="{{ route('invoice-payments.show', $user->id) }}" class="btn btn-light">
                            Clear Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Payments Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment Cycle</th>
                        <th>Amount</th>
                        <th>Tax</th>
                        <th>Net Payable</th>
                        <th>Status</th>
                        <th>Invoice</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>{{ $payment->paymentCycle->description ?? 'N/A' }}</td>
                            <td>
                                <span class="editable" 
                                      data-type="amount_payable" 
                                      data-id="{{ $payment->id }}">
                                    {{ number_format($payment->amount_payable, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="editable" 
                                      data-type="tax" 
                                      data-id="{{ $payment->id }}">
                                    {{ number_format($payment->tax, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="editable" 
                                      data-type="net_payable" 
                                      data-id="{{ $payment->id }}">
                                    {{ number_format($payment->net_payable, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $payment->status == 'Completed' ? 'success' : ($payment->status == 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td>
                                @if($payment->invoice_file || $payment->new_invoice_file)
                                    <a href="{{ asset('storage/' . ($payment->invoice_file ?? $payment->new_invoice_file)) }}" 
                                       target="_blank"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-file-invoice"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">No Invoice</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('invoice-payments.update-status', $payment) }}" 
                                      method="POST" 
                                      class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" 
                                            class="form-control form-control-sm status-select"
                                            style="width: 120px;">
                                        <option value="">Select Status</option>
                                        <option value="Completed" {{ $payment->status == 'Completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option value="Rejected" {{ $payment->status == 'Rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                    
                                    <textarea name="rejection_reason" 
                                              class="form-control form-control-sm mt-2 rejection-reason" 
                                              placeholder="Enter rejection reason"
                                              style="display: {{ $payment->status == 'Rejected' ? 'block' : 'none' }};">{{ $payment->rejection_reason }}</textarea>
                                              
                                    <button type="submit" class="btn btn-sm btn-primary mt-2">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inline editing functionality
    $('.editable').on('click', function() {
        var $span = $(this);
        var currentValue = $span.text().trim().replace(/,/g, '');
        var input = $('<input>', {
            type: 'number',
            step: '0.01',
            value: currentValue,
            class: 'form-control form-control-sm'
        });
        
        $span.hide().after(input);
        input.focus();

        input.on('blur', function() {
            var newValue = $(this).val().trim();
            if (newValue !== currentValue) {
                var $loading = $('<span class="loading ms-2"></span>');
                $span.after($loading);
                
                $.ajax({
                    url: '{{ route("invoice-payments.update-field") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $span.data('id'),
                        field: $span.data('type'),
                        value: newValue
                    },
                    success: function(response) {
                        $span.text(response.formatted_value);
                        // Update totals if needed
                        updateTotals();
                    },
                    error: function(xhr) {
                        alert('Error updating value: ' + xhr.responseJSON.message);
                        $span.text(numberFormat(currentValue));
                    },
                    complete: function() {
                        $loading.remove();
                    }
                });
            }
            $span.show();
            $(this).remove();
        });

        input.on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $(this).blur();
            }
        });
    });

    // Handle status change
    $('.status-select').on('change', function() {
        var rejectionReason = $(this).closest('form').find('.rejection-reason');
        if ($(this).val() === 'Rejected') {
            rejectionReason.slideDown();
        } else {
            rejectionReason.slideUp();
        }
    });

    // Helper function to format numbers
    function numberFormat(number) {
        return parseFloat(number).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    $('#paymentApproval').on('change', function() {
        const $toggle = $(this);
        const $statusLabel = $('.status-label');
        const userId = $toggle.data('user-id');
        const isApproved = $toggle.prop('checked');

        $.ajax({
            url: "{{ route('invoice-payments.update-payment-status', $user->id) }}",
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                approved_for_payment: isApproved
            },
            success: function(response) {
                $statusLabel.text(isApproved ? 'Approved for Payment' : 'Not Approved');
                // Show success message
                toastr.success('Payment approval status updated successfully');
            },
            error: function(xhr) {
                // Revert toggle on error
                $toggle.prop('checked', !isApproved);
                $statusLabel.text(!isApproved ? 'Approved for Payment' : 'Not Approved');
                // Show error message
                toastr.error('Error updating payment approval status');
            }
        });
    });
    
    // Function to update totals after editing
    function updateTotals() {
        $.get(window.location.href, function(data) {
            // Update the totals boxes with new values
            // This would require returning JSON data instead of a full page
            // Implementation depends on your backend setup
        });
    }
});
</script>
@endpush