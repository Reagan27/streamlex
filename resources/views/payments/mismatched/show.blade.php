@extends('layouts.app')

@section('page-title', __('Mismatched Payment Details'))
@section('page-heading', __('Mismatched Payment Details'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payment Details</h5>
            <div>
                <button class="btn btn-primary" 
                        data-toggle="modal" 
                        data-target="#editPaymentModal">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form action="{{ route('mismatched-payments.destroy', $payment->id) }}" 
                      method="POST" 
                      style="display: inline-block"
                      onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th width="30%">Imported Name</th>
                        <td>{{ $payment->imported_name }}</td>
                    </tr>
                    <tr>
                        <th>ID Number</th>
                        <td>{{ $payment->id_number }}</td>
                    </tr>
                    <tr>
                        <th>Payment Cycle</th>
                        <td>{{ $payment->paymentCycle->description }}</td>
                    </tr>
                    <tr>
                        <th>Amount Payable</th>
                        <td>{{ number_format($payment->amount_payable, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Tax</th>
                        <td>{{ number_format($payment->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Productivity</th>
                        <td>{{ $payment->productivity }}%</td>
                    </tr>
                    @if($payment->resolution_notes)
                    <tr>
                        <th>Resolution Notes</th>
                        <td>{{ $payment->resolution_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mismatched-payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Number</label>
                        <input type="text" 
                               name="id_number" 
                               class="form-control @error('id_number') is-invalid @enderror" 
                               value="{{ old('id_number', $payment->id_number) }}" 
                               required>
                        @error('id_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Amount Payable</label>
                        <input type="number" 
                               step="0.01" 
                               name="amount_payable" 
                               class="form-control @error('amount_payable') is-invalid @enderror" 
                               value="{{ old('amount_payable', $payment->amount_payable) }}" 
                               required>
                        @error('amount_payable')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tax</label>
                        <input type="number" 
                               step="0.01" 
                               name="tax" 
                               class="form-control @error('tax') is-invalid @enderror" 
                               value="{{ old('tax', $payment->tax) }}" 
                               required>
                        @error('tax')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Productivity (%)</label>
                        <input type="number" 
                               step="0.01" 
                               name="productivity" 
                               class="form-control @error('productivity') is-invalid @enderror" 
                               value="{{ old('productivity', $payment->productivity) }}" 
                               min="0" 
                               max="100" 
                               required>
                        @error('productivity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Resolution Notes</label>
                        <textarea name="resolution_notes" 
                                  class="form-control @error('resolution_notes') is-invalid @enderror" 
                                  rows="3">{{ old('resolution_notes', $payment->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Show modal if there are validation errors
    @if($errors->any())
        $(document).ready(function() {
            $('#editPaymentModal').modal('show');
        });
    @endif
</script>
@endpush