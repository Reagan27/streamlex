@extends('layouts.app')

@section('page-title', __('My Payments'))
@section('page-heading', __('My Payments'))

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Payment Cycle</th>
                        <th>Productivity</th>
                        <th>Amount Payable</th>
                        <th>Status</th>
                        <th>Tax</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_cycle_description }}</td>
                            <td>{{ $payment->productivity }}</td>
                            <td>{{ $payment->amount_payable }}</td>
                            <td>{{ $payment->status }}</td>
                            <td>{{ $payment->tax }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $payments->links() }}
        </div>
    </div>
@endsection