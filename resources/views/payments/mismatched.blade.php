@extends('layouts.app')

@section('page-title', __('Mismatched Payments'))
@section('page-heading', __('Mismatched Payments'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('payments.index') }}">@lang('Payments')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Mismatched Payments')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if($mismatchedPayments->isEmpty())
            <div class="alert alert-info">No mismatched payments found.</div>
        @else
        <table class="table table-striped">
    <thead>
        <tr>
            <th>User</th>
            <th>ID Number</th>
            <th>Amount</th>
            <th>Cycle</th>
            <th>Productivity</th>
            <th>Tax</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mismatchedPayments as $payment)
            <tr>
                <td>{{ $payment->matched_name ?? $payment->imported_name ?? 'N/A' }}</td>
                <td>{{ $payment->id_number ?? 'N/A' }}</td>
                <td>{{ number_format($payment->amount_payable, 2) }}</td>
                <td>{{ $payment->paymentCycle->description }}</td>
                <td>{{ $payment->productivity }}%</td>
                <td>{{ number_format($payment->tax, 2) }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('payments.show', $payment->id) }}" 
                           class="btn btn-icon"
                           data-toggle="tooltip" 
                           title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        <a href="{{ route('banking.show', ['idNumber' => $payment->id_number]) }}"
                           class="btn btn-icon {{ !$payment->id_number ? 'disabled' : '' }}"
                           data-toggle="tooltip" 
                           title="Bank Details">
                            <i class="fas fa-bank"></i>
                        </a>

                        <button type="submit" 
                                class="btn btn-icon"
                                data-toggle="tooltip" 
                                title="Remove Record"
                                form="delete-form-{{ $payment->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        
                        <form id="delete-form-{{ $payment->id }}"
                              action="{{ route('payments.destroy', $payment->id) }}"
                              method="POST"
                              style="display: none"
                              onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
            {{ $mismatchedPayments->links() }}
        @endif
    </div>
</div>
@endsection