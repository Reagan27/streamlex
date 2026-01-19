@extends('layouts.app')

@section('page-title', __('Payment History'))
@section('page-heading', __('Payment History for ' . $user->first_name . ' ' . $user->last_name))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('payments.index') }}">@lang('Payments')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Payment History')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">@lang('Payment History')</h5>

            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>@lang('Payment Cycle')</th>
                        <th>@lang('Invoice Number')</th>
                        <th>@lang('Amount Payable')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($payments))
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_cycle_description }}</td>
                                <td>{{ $payment->invoice_number ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount_payable, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'Completed' ? 'success' : 'warning' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info"
                                       title="@lang('View Payment Details')" data-toggle="tooltip" data-placement="top">
                                        @lang('View Details')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5"><em>@lang('No payments found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! $payments->links() !!}
@stop

@section('scripts')
    @parent
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@stop