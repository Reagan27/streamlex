@extends('layouts.app')

@section('page-title', __('Invoice Payments'))
@section('page-heading', __('Invoice Payments'))

@section('styles')
<style>
    .search-box {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
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
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Invoice Payments</h4>
        <div class="action-buttons">
            <a href="{{ route('invoice-payments.export', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Search and Filter Box -->
        <div class="search-box">
            <form method="GET" action="{{ route('invoice-payments.index') }}" id="filter-form" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by name, email, or phone"
                               value="{{ request('search') }}">
                        @if(request('search'))
                            <div class="input-group-append">
                                <a href="{{ route('invoice-payments.index', request()->except('search')) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="county" class="form-control select2" id="county-select">
                        <option value="">All Counties</option>
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" 
                                    {{ request('county') == $county->id ? 'selected' : '' }}>
                                {{ $county->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
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
                <span class="total-label">Total Invoices</span>
                <span class="total-value">{{ number_format($totals->total_invoices ?? 0) }}/{{ number_format($totals->total_payments ?? 0) }}</span>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>County</th>
                        <th>Total Amount</th>
                        <th>Total Tax</th>
                        <th>Total Net</th>
                        <th>Invoices</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->county->name ?? 'N/A' }}</td>
                            <td>{{ number_format($user->total_amount_payable ?? 0, 2) }}</td>
                            <td>{{ number_format($user->total_tax ?? 0, 2) }}</td>
                            <td>{{ number_format($user->total_net_payable ?? 0, 2) }}</td>
                            <td>{{ $user->invoice_count }}/{{ $user->total_payments }}</td>
                            <td>
                            <a href="{{ route('invoice-payments.show', ['user' => $user->id]) }}" 
   class="btn btn-info btn-sm">
    <i class="fas fa-eye"></i> View Details
</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Auto-submit filters on change
    $('#county-select').on('change', function() {
        $('#filter-form').submit();
    });

    // Auto-dismiss alerts
    $('.alert-dismissible').delay(5000).fadeOut(500);
});
</script>
@endpush