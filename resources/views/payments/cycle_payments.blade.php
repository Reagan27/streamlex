<table class="table table-borderless table-striped">
    <thead>
    <tr>
        <th>@lang('User')</th>
        <th>@lang('Email')</th>
        <th>@lang('ID Number')</th>
        <th>@lang('Productivity')</th>
        <th>@lang('Amount Payable')</th>
        <th>@lang('Status')</th>
        <th>@lang('Tax')</th>
        <th>@lang('Action')</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($payments as $payment)
        <tr>
            <td>{{ $payment->user->first_name . ' ' . $payment->user->last_name }}</td>
            <td>{{ $payment->user->email }}</td>
            <td>{{ $payment->id_number }}</td>
            <td>
                <span class="editable editable-click" 
                      data-type="text" 
                      data-pk="{{ $payment->id }}" 
                      data-name="productivity" 
                      data-url="{{ route('payments.update-field', $payment->id) }}"
                      data-title="Enter productivity">
                    {{ $payment->productivity }}
                </span>
            </td>
            <td>
                <span class="editable editable-click" 
                      data-type="text" 
                      data-pk="{{ $payment->id }}" 
                      data-name="amount_payable" 
                      data-url="{{ route('payments.update-field', $payment->id) }}"
                      data-title="Enter amount payable">
                    {{ number_format($payment->amount_payable, 2) }}
                </span>
            </td>
            <td>
                <span class="badge bg-{{ $payment->status == 'Completed' ? 'success' : 'warning' }}">
                    {{ $payment->status }}
                </span>
            </td>
            <td>
                <span class="editable editable-click" 
                      data-type="text" 
                      data-pk="{{ $payment->id }}" 
                      data-name="tax" 
                      data-url="{{ route('payments.update-field', $payment->id) }}"
                      data-title="Enter tax">
                    {{ number_format($payment->tax, 2) }}
                </span>
            </td>
            <td>
                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-icon"
                   title="@lang('View Payment Details')" data-toggle="tooltip" data-placement="top">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No payments found for this cycle.</td>
        </tr>
    @endforelse
    </tbody>
</table>