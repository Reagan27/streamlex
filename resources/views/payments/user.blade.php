@extends('layouts.app')

@section('page-title', __('My Payments'))
@section('page-heading', __('My Payments'))

@section('styles')
<style>
    .payment-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-completed { background-color: #d4edda; color: #155724; }
    .status-rejected { background-color: #f8d7da; color: #721c24; }
    .amount-column {
        font-family: monospace;
        text-align: right;
    }
    .productivity-badge {
        background-color: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }
    .summary-card {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .etims-section {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .etims-title {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .etims-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .summary-section {
        padding: 1rem;
        margin: 1rem 0;
    }
    .total-amount {
        font-size: 1rem;
        font-weight: bold;
        color: #2c3e50;
    }
    .sub-text {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .summary-details {
        font-size: 0.875rem;
        color: #495057;
    }
    .action-section {
        text-align: right;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')

@php
    $paymentsCollection = $pagedPayments->getCollection();
    
    $aggregatedPayments = $paymentsCollection->filter(function($payment) {
        return $payment->invoice_type === 'aggregated';
    });
    
    $individualPayments = $paymentsCollection->filter(function($payment) {
        return $payment->invoice_type === 'individual' || $payment->invoice_type === null;
    });
@endphp

<!-- Combined Card for All Payments -->
<div class="card summary-card">
    <!-- Show ETIMS Section if there are aggregated payments -->
    @if($aggregatedPayments->isNotEmpty())
        <div class="row mb-4">
            <!-- ETIMS Section -->
            <div class="col-md-4">
                <div class="etims-section h-100">
                    <div class="etims-title">ETIMS Invoice Generation Details</div>
                    <div class="etims-info">Please use the following information:</div>
                    <div class="etims-info"><strong>Selistar PIN:</strong> P052364620V</div>
                    <div class="etims-info"><strong>Selistar Phone No:</strong> 0748778304</div>
                    <div class="etims-info"><strong>Description:</strong> Data Collection Professional fee</div>
                </div>
            </div>

            <!-- Aggregated Invoice Summary -->
            <div class="col-md-4">
            <div class="summary-section h-100">
    @if($aggregatedPayments->whereNull('new_invoice_file')->count() > 0 || 
        $aggregatedPayments->where('status', 'Rejected')->count() > 0)
        <h5 class="mb-3">Aggregated Invoice Amount</h5>
        <div class="summary-details">
            
        <div>
    Invoice Amount: <span class="total-amount">
        KES {{ number_format($aggregatedPayments->sum('net_payable') + $aggregatedPayments->sum('tax'), 2) }}
    </span>
</div>
        </div>
    @endif
</div>
            </div>

            <!-- Aggregated Action Section -->
            <div class="col-md-4">
                <div class="action-section h-100">
                    @if($aggregatedPayments->where('status', 'Rejected')->count() > 0)
                        @php
                            $rejectedPayment = $aggregatedPayments->where('status', 'Rejected')->first();
                        @endphp
                        <div class="alert alert-warning mb-2">
                            <div class="mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Combined invoice was rejected
                            </div>
                            @if($rejectedPayment->rejection_reason)
                                <div class="text-danger">
                                    <strong>Reason:</strong> {{ $rejectedPayment->rejection_reason }}
                                </div>
                            @endif
                        </div>
                        <button class="btn btn-warning btn-lg w-100" 
                                data-toggle="modal" 
                                data-target="#aggregatedInvoiceModal">
                            <i class="fas fa-upload"></i> Reupload Combined Invoice
                        </button>
                    @elseif($aggregatedPayments->whereNull('new_invoice_file')->count() > 0)
                        <button class="btn btn-primary btn-lg w-100" 
                                data-toggle="modal" 
                                data-target="#aggregatedInvoiceModal">
                            <i class="fas fa-upload"></i> Upload Combined Invoice
                        </button>
                    @else
                        @php
                            $latestAggregated = $aggregatedPayments->whereNotNull('new_invoice_file')->first();
                        @endphp
                        <div class="alert alert-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle"></i> All aggregated payments invoiced</span>
                            </div>
                            @if($latestAggregated)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $latestAggregated->new_invoice_file) }}" 
                                       target="_blank" 
                                       class="btn btn-info btn-block">
                                        <i class="fas fa-file-invoice"></i> View Combined Invoice
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Combined Payments Table -->
    <div class="table-responsive">
        <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>Payment Cycle</th>
                  
                    <th>Productivity</th>
                    <th>Total</th>
                    <th>Taxable</th>
                    <th>Tax</th>
                    <th>Advanced</th>
                    <th>Net</th>
                    <th>Status</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentsCollection as $payment)
                    <tr>
                        <td>{{ $payment->paymentCycle->description }}</td>
                      
                        <td class="text-center">
                            <span class="productivity-badge">
                                {{ number_format($payment->productivity, 1) }}
                            </span>
                        </td>
                        <td class="amount-column">
                            {{ number_format($payment->amount_payable, 2) }}
                        </td>
                        <td class="amount-column">
                            {{ number_format($payment->taxable_amount, 2) }}
                        </td>
                        <td class="amount-column">
                            {{ number_format($payment->tax, 2) }}
                        </td>
                        <td class="amount-column">
                            {{ number_format($payment->advance_pay, 2) }}
                        </td>
                        <td class="amount-column">
                            {{ number_format($payment->net_payable, 2) }}
                        </td>
                        <td>
                            <span class="payment-status status-{{ strtolower($payment->status) }}">
                                {{ $payment->status }}
                            </span>
                            @if($payment->status == 'Rejected' && $payment->rejection_reason)
                                <div class="mt-1">
                                    <small class="text-danger">{{ $payment->rejection_reason }}</small>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($payment->invoice_type === 'aggregated')
                                <small class="text-muted">Combined invoice above</small>
                            @else
                                @if($payment->invoice_file)
                                    <div class="btn-group-vertical">
                                        <a href="{{ asset('storage/' . $payment->invoice_file) }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-file-invoice"></i> View
                                        </a>
                                        @if($payment->status == 'Rejected')
                                            <button class="btn btn-sm btn-warning individual-upload-invoice" 
                                                    data-payment-id="{{ $payment->id }}">
                                                <i class="fas fa-upload"></i> Reupload
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <button class="btn btn-sm btn-primary individual-upload-invoice" 
                                            data-payment-id="{{ $payment->id }}">
                                        <i class="fas fa-upload"></i> Upload
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-3">
    {{ $pagedPayments->links() }}
</div>

<!-- Aggregated Invoice Upload Modal -->
<div class="modal fade" id="aggregatedInvoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Combined Invoice</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="aggregatedInvoiceForm" action="{{ route('payments.upload-combined-invoice') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                <div class="alert alert-info">
  
    Invoice Amount: <span class="total-amount">
        KES {{ number_format($aggregatedPayments->sum('net_payable') + $aggregatedPayments->sum('tax'), 2) }}
    </span>

    <p class="mb-0">This invoice will be applied to all pending aggregated payments.</p>
</div>

                    <div class="form-group">
                        <label for="invoice_number">Invoice Number</label>
                        <input type="text" class="form-control" id="invoice_number" 
                               name="invoice_number" required>
                    </div>
                    <div class="form-group">
                        <label for="invoice_file">Invoice File</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="invoice_file" 
                                   name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <label class="custom-file-label" for="invoice_file">Choose file</label>
                        </div>
                        <small class="form-text text-muted">
                            Accepted formats: PDF, JPG, JPEG, PNG (Max size: 2MB)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Individual Invoice Upload Modal -->
<div class="modal fade" id="individualInvoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Individual Invoice</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="individualInvoiceForm">
                @csrf
                <input type="hidden" id="individual_payment_id" name="payment_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="individual_invoice_number">Invoice Number</label>
                        <input type="text" class="form-control" id="individual_invoice_number" 
                               name="invoice_number" required>
                    </div>
                    <div class="form-group">
                        <label for="individual_invoice_file">Invoice File</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="individual_invoice_file" 
                                   name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <label class="custom-file-label" for="individual_invoice_file">Choose file</label>
                        </div>
                        <small class="form-text text-muted">
                            Accepted formats: PDF, JPG, JPEG, PNG (Max size: 2MB)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize variables
    let currentPaymentId;

    // Individual invoice upload button click handler
    $('.individual-upload-invoice').on('click', function() {
        currentPaymentId = $(this).data('payment-id');
        $('#individual_payment_id').val(currentPaymentId);
        $('#individualInvoiceModal').modal('show');
    });

    // File input label updates for both modals
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    // Aggregated Invoice Form Submit
    $('#aggregatedInvoiceForm').on('submit', function(e) {
        e.preventDefault();
        handleFormSubmit($(this), 'aggregatedInvoiceModal');
    });

    // Individual Invoice Form Submit
    $('#individualInvoiceForm').on('submit', function(e) {
        e.preventDefault();
        handleFormSubmit($(this), 'individualInvoiceModal', '/payments/' + currentPaymentId + '/upload-invoice');
    });

    // Generic form submission handler for both types of uploads
    function handleFormSubmit(form, modalId, customUrl = null) {
        let submitBtn = form.find('button[type="submit"]');
        let formData = new FormData(form[0]);
        
        submitBtn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
        
        $.ajax({
            url: customUrl || form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#' + modalId).modal('hide');
                location.reload();
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false)
                        .html('<i class="fas fa-upload"></i> Upload Invoice');
                
                let errorMessage = 'Error uploading invoice. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    }

    // Reset forms when modals are closed
    $('#aggregatedInvoiceModal, #individualInvoiceModal').on('hidden.bs.modal', function() {
        let form = $(this).find('form');
        
        form[0].reset();
        form.find('.custom-file-label').html('Choose file');
        form.find('button[type="submit"]')
            .prop('disabled', false)
            .html('<i class="fas fa-upload"></i> Upload Invoice');
        form.find('.alert-danger').remove();
    });

    // Form validation
    function validateForm(form) {
        let isValid = true;
        
        form.find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        let fileInput = form.find('input[type="file"]')[0];
        if (fileInput && fileInput.files[0]) {
            if (fileInput.files[0].size > 2 * 1024 * 1024) {
                isValid = false;
                alert('File size must be less than 2MB');
            }
        }

        return isValid;
    }

    // Add validation before form submission
    $('#aggregatedInvoiceForm, #individualInvoiceForm').on('submit', function(e) {
        if (!validateForm($(this))) {
            e.preventDefault();
            return false;
        }
    });

    // Remove invalid styling on input
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // File type validation
    $('.custom-file-input').on('change', function() {
        let fileName = this.files[0].name;
        let fileType = fileName.split('.').pop().toLowerCase();
        let allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        
        if (!allowedTypes.includes(fileType)) {
            alert('Invalid file type. Allowed types: PDF, JPG, JPEG, PNG');
            this.value = '';
            $(this).next('.custom-file-label').html('Choose file');
        }
    });
});
</script>
@endpush