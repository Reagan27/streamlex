@extends('layouts.app')

@section('page-title', __('Payment Details'))
@section('page-heading', __('Payment Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('payments.index') }}">@lang('Payments')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Payment Details')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Payment Details
                @if($payment->status === 'Rejected')
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </h5>
            <div>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPaymentModal">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form action="{{ route('payments.destroy', $payment->id) }}" 
                      method="POST" 
                      style="display: inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this payment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Imported Name</th>
                        <td>{{ $payment->imported_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>ID Number</th>
                        <td>{{ $payment->id_number ?? 'N/A' }}</td>
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
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $payment->status == 'Completed' ? 'success' : ($payment->status == 'Rejected' ? 'danger' : 'warning') }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                    </tr>
                    @if($payment->rejection_reason)
                    <tr>
                        <th>Rejection Reason</th>
                        <td class="text-danger">{{ $payment->rejection_reason }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('payments.update', $payment) }}" method="POST">
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
                        <label for="id_number">ID Number</label>
                        <input type="text" 
                               name="id_number" 
                               id="id_number" 
                               class="form-control @error('id_number') is-invalid @enderror" 
                               value="{{ old('id_number', $payment->id_number) }}" 
                               required>
                        @error('id_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount_payable">Amount Payable</label>
                        <input type="number" 
                               step="0.01" 
                               name="amount_payable" 
                               id="amount_payable" 
                               class="form-control @error('amount_payable') is-invalid @enderror" 
                               value="{{ old('amount_payable', $payment->amount_payable) }}" 
                               required>
                        @error('amount_payable')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tax">Tax</label>
                        <input type="number" 
                               step="0.01" 
                               name="tax" 
                               id="tax" 
                               class="form-control @error('tax') is-invalid @enderror" 
                               value="{{ old('tax', $payment->tax) }}" 
                               required>
                        @error('tax')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="productivity">Productivity (%)</label>
                        <input type="number" 
                               step="0.01" 
                               name="productivity" 
                               id="productivity" 
                               class="form-control @error('productivity') is-invalid @enderror" 
                               value="{{ old('productivity', $payment->productivity) }}" 
                               required>
                        @error('productivity')
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
@endsection