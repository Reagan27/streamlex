@extends('layouts.app')

@section('page-title', __('Payments'))
@section('page-heading', __('Payments'))

@section('styles')
<style>
    .editable {
        border-bottom: 1px dashed blue;
        cursor: pointer;
    }
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(0, 0, 0, 0.3);
        border-radius: 50%;
        border-top-color: #000;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
    @-webkit-keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }

    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }

    .btn-icon {
        padding: 0.375rem 0.75rem;
    }

    .badge {
        padding: 0.5em 0.75em;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }

    .search-box {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            @if($paymentCycles->isNotEmpty())
                <form action="{{ route('payments.index') }}" method="GET" class="mr-3">
                    <label for="cycle-select" class="mr-2">Payment Cycle:</label>
                    <select id="cycle-select" 
                            name="cycle_id" 
                            class="form-control d-inline-block" 
                            style="width: 300px;">
                        @foreach($paymentCycles as $cycle)
                            <option value="{{ $cycle->id }}" 
                                    {{ optional($selectedCycle)->id == $cycle->id ? 'selected' : '' }}>
                                {{ $cycle->description }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($selectedCycle)
                    <button type="button" 
                            class="btn btn-icon edit ml-2"
                            data-toggle="modal"
                            data-target="#editCycleModal"
                            data-cycle-id="{{ $selectedCycle->id }}"
                            data-cycle-description="{{ $selectedCycle->description }}"
                            data-cycle-invoicable="{{ $selectedCycle->is_invoicable ? 'true' : 'false' }}"
                            title="Edit Cycle">
                        <i class="fas fa-edit"></i>
                    </button>

                    <form action="{{ route('payment-cycles.destroy', $selectedCycle->id) }}"
                          method="POST"
                          class="ml-2"
                          onsubmit="return confirm('Are you sure you want to delete this entire payment cycle and all its payments?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon" data-toggle="tooltip" 
                                title="Delete Current Cycle">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            @else
                <div class="alert alert-info mb-0">
                    No payment cycles available. Please create a new cycle first.
                </div>
            @endif
        </div>

        <div>
            <a href="{{ route('mismatched-payments.index') }}" 
               class="btn btn-warning btn-icon"
               data-toggle="tooltip" 
               title="Mismatched Payments">
                <i class="fas fa-exclamation-triangle"></i>
            </a>
            <a href="{{ route('payments.import') }}" class="btn btn-primary ml-2">
                <i class="fas fa-upload"></i> Import Payments
            </a>
        </div>
    </div>

    @if($selectedCycle)
        <!-- Search Box -->
        <div class="search-box mx-3 mt-3">
            <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-center">
                <input type="hidden" name="cycle_id" value="{{ $selectedCycle->id }}">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by user name, email or ID number"
                               value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            @if(request()->has('search'))
                                <a href="{{ route('payments.index', ['cycle_id' => $selectedCycle->id]) }}" 
                                   class="btn btn-icon">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
 
        <div class="mt-3 ml-5">
            <strong>Cycle Totals:</strong>
            <span class="ml-2">Amount: KES {{ number_format($cycleTotals->total_amount ?? 0, 2) }}</span>
            <span class="ml-2">Tax: KES {{ number_format($cycleTotals->total_tax ?? 0, 2) }}</span>
            <span class="ml-2">Invoices: {{ number_format($cycleTotals->total_invoices ?? 0) }}/{{ number_format($cycleTotals->total_possible_invoices)}}</span>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($payments->isEmpty())
                <div class="alert alert-info">
                    @if(request()->has('search'))
                        No payments found matching your search criteria.
                    @else
                        No payments found for the selected cycle.
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Net Payable</th>
                                <th>Tax</th>
                                <th>Productivity</th>
                                <th>Status</th>
                                <th>
                                    Invoice
                                    <a href="{{ request()->fullUrlWithQuery(['sort_invoices' => request('sort_invoices') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-muted ml-2">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                    <td>
                                    <span class="editable" data-type="amount_payable" data-id="{{ $payment->id }}">
        {{ number_format($payment->amount_payable, 2) }}
    </span>
                                    </td>
                                    <td>
                                    <span class="editable" data-type="net_payable" data-id="{{ $payment->id }}">
        {{ number_format($payment->net_payable, 2) }}
    </span>
                                    </td>
                                    <td>
                                        <span class="editable" data-type="tax" data-id="{{ $payment->id }}">
                                            {{ number_format($payment->tax, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="editable" data-type="productivity" data-id="{{ $payment->id }}">
                                            {{ $payment->productivity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'Approved' ? 'success' : ($payment->status == 'Rejected' ? 'danger' : 'warning') }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td>
    @if($payment->invoice_file)
        <a href="{{ asset('storage/' . $payment->invoice_file) }}" 
           target="_blank"
           class="btn btn-sm btn-info">
            <i class="fas fa-file-invoice"></i> View
        </a>
    @elseif($payment->new_invoice_file)
        <a href="{{ asset('storage/' . $payment->new_invoice_file) }}" 
           target="_blank"
           class="btn btn-sm btn-info">
            <i class="fas fa-file-invoice"></i> View
        </a>
    @else
        <span class="text-muted">No Invoice</span>
    @endif
</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('payments.show', $payment->id) }}" 
                                               class="btn btn-icon"
                                               data-toggle="tooltip" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <form action="{{ route('payments.destroy', $payment->id) }}"
                                                  method="POST"
                                                  style="display: inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-icon"
                                                        data-toggle="tooltip" 
                                                        title="Remove Record">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <form action="{{ route('payments.update-status', $payment) }}" 
                                              method="POST" 
                                              class="mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" 
        class="form-control form-control-sm mb-2" 
        onchange="toggleRejectionReason(this)">
        <option>Select Option</option>
    <option value="Approved" {{ $payment->status == 'Approved' ? 'selected' : '' }}>
        Approved
    </option>
    <option value="Rejected" {{ $payment->status == 'Rejected' ? 'selected' : '' }}>
        Rejected
    </option>
</select>
                                            <textarea name="rejection_reason" 
                                                      class="form-control form-control-sm mb-2" 
                                                      style="display: {{ $payment->status == 'Rejected' ? 'block' : 'none' }};" 
                                                      placeholder="Enter reason for rejection">{{ $payment->rejection_reason }}</textarea>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $payments->appends(request()->except('page'))->links() }}
            @endif
        </div>
    @endif
</div>

<!-- Edit Payment Cycle Modal -->
<div class="modal fade" id="editCycleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editCycleForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="is_invoicable" value="0">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment Cycle</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="cycleInvoicable"
                                   name="is_invoicable" 
                                   value="1">
                            <label class="custom-control-label" for="cycleInvoicable">
                                Require Invoices
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#cycle-select').select2({
        placeholder: 'Search for a payment cycle...',
        allowClear: true,
        width: '300px',
        theme: 'classic'
    });

    // Trigger form submit when selection changes
    $('#cycle-select').on('select2:select', function() {
        $(this).closest('form').submit();
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Editable fields functionality
    $('.editable').on('click', function() {
    var $span = $(this);
    var currentValue = $span.text().trim().replace(/,/g, ''); // Remove commas from numbers
    var input = $('<input>', {
        type: 'text',
        value: currentValue,
        class: 'form-control form-control-sm'
    });
    
    $span.hide().after(input);
    input.focus();

    input.on('blur', function() {
        var newValue = $(this).val().trim();
        if (newValue !== currentValue) {
            var $loading = $('<div class="loading"></div>');
            $span.after($loading);
            
            $.ajax({
                url: '{{ route("payments.update-field") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: $span.data('id'),
                    field: $span.data('type'),
                    value: newValue
                },
                success: function(response) {
                    // Format numbers with commas if it's not productivity
                    if ($span.data('type') !== 'productivity') {
                        $span.text(parseFloat(newValue).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                    } else {
                        $span.text(newValue);
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Error updating field. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                    // Restore the original formatted value
                    if ($span.data('type') !== 'productivity') {
                        $span.text(parseFloat(currentValue).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                    } else {
                        $span.text(currentValue);
                    }
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

    // Prevent non-numeric input (except decimal point and backspace)
    input.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(this).blur();
            return;
        }
        
        // Allow: backspace, delete, tab, escape, enter, decimal point
        if (e.which === 46 || // decimal point
            e.which === 8  || // backspace
            e.which === 9  || // tab
            e.which === 27 || // escape
            e.which === 13 || // enter
            // Allow numbers
            (e.which >= 48 && e.which <= 57)) {
            // Check if it's a decimal point
            if (e.which === 46 && $(this).val().indexOf('.') !== -1) {
                e.preventDefault(); // Prevent multiple decimal points
            }
            return;
        }
        e.preventDefault();
    });
});
    // Edit Cycle Modal Handling
    $('#editCycleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        var form = modal.find('form');
        
        form.attr('action', '/payment-cycles/' + button.data('cycle-id'));
        form.find('[name="description"]').val(button.data('cycle-description'));
        
        var isInvoicable = button.data('cycle-invoicable') === true || 
                          button.data('cycle-invoicable') === 'true';
        form.find('#cycleInvoicable').prop('checked', isInvoicable);
    });

    // Handle form submission
    $('#editCycleForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var isInvoicable = $('#cycleInvoicable').is(':checked');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                description: form.find('[name="description"]').val(),
                is_invoicable: isInvoicable ? 1 : 0
            },
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
                alert('Error updating payment cycle. Please try again.');
            }
        });
    });

    // Alert auto-dismiss
    $('.alert-dismissible').each(function() {
        var $alert = $(this);
        setTimeout(function() {
            $alert.alert('close');
        }, 5000);
    });
});

function toggleRejectionReason(selectElement) {
    var rejectionReasonTextarea = selectElement.parentNode.querySelector('textarea[name="rejection_reason"]');
    rejectionReasonTextarea.style.display = selectElement.value === 'Rejected' ? 'block' : 'none';
    
    if (selectElement.value !== 'Rejected') {
        rejectionReasonTextarea.value = '';
    }
}
</script>
@endpush