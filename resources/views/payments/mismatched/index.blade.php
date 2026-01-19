@extends('layouts.app')

@section('page-title', __('Mismatched Payments'))
@section('page-heading', __('Mismatched Payments'))

@section('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css" rel="stylesheet">
<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .actions-column {
        min-width: 100px;
    }
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header Card -->
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if($mismatchedPayments->isEmpty())
                <div class="alert alert-info">
                    No mismatched payments found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped" id="mismatched-table">
                        <thead>
                            <tr>
                                <th>Imported Name</th>
                                <th>ID Number</th>
                                <th>Amount</th>
                                <th>Net Payable</th>
                                <th>Cycle</th>
                                <th>Productivity</th>
                                <th>Tax</th>
                                <th class="actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mismatchedPayments as $payment)
                                <tr>
                                    <td>{{ $payment->imported_name }}</td>
                                    <td>{{ $payment->id_number }}</td>
                                    <td>{{ number_format($payment->amount_payable, 2) }}</td>
                                    <td>{{ number_format($payment->net_payable, 2) }}</td>
                                    <td>{{ $payment->paymentCycle->description }}</td>
                                    <td>{{ number_format($payment->productivity, 1) }}%</td>
                                    <td>{{ number_format($payment->tax, 2) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary"
                                                    onclick="openEditModal('{{ $payment->id }}', '{{ $payment->id_number }}')"
                                                    title="Edit ID">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('mismatched-payments.destroy', $payment->id) }}"
                                                  method="POST"
                                                  style="display: inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editForm" method="POST" action="{{ route('mismatched-payments.update', ':payment_id') }}">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit ID Number</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_number">ID Number</label>
                        <input type="text" 
                               name="id_number" 
                               id="id_number" 
                               class="form-control" 
                               required>
                        <input type="hidden" id="payment_id" name="payment_id">
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#mismatched-table').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Export to Excel',
                className: 'btn btn-success',
                title: 'Mismatched Payments',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Exclude actions column
                },
                action: function(e, dt, button, config) {
                    window.location = '{{ route("mismatched-payments.export") }}';
                }
            }
        ],
        pageLength: 15,
        responsive: true,
        order: [[4, 'desc'], [0, 'asc']], // Sort by cycle desc, then name asc
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Handle form submission
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const paymentId = $('#payment_id').val();
        
        // Update form action URL
        let action = $(this).attr('action').replace(':payment_id', paymentId);
        
        $.ajax({
            url: action,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                id_number: $('#id_number').val()
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    $('#editModal').modal('hide');
                    alert(response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON;
                alert(error?.message || 'Error updating ID. Please try again.');
            }
        });
    });

    // Auto-dismiss alerts
    $('.alert-dismissible').delay(5000).fadeOut(350);
});

function openEditModal(paymentId, currentIdNumber) {
    $('#payment_id').val(paymentId);
    $('#id_number').val(currentIdNumber);
    $('#editModal').modal('show');
}
</script>
@endpush