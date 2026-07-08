<div class="card overflow-hidden">
    <h6 class="card-header d-flex align-items-center justify-content-between">
        @lang('CONTRACTS')
    </h6>

    <div class="card-body p-0">
        @if (count($latestContracts))
            <ul class="list-group list-group-flush">
                @foreach ($latestContracts as $contract)
                    <li class="list-group-item list-group-item-action px-4 py-3">
                        <a href="{{ route('contracts.show', $contract['id']) }}" class="d-flex text-dark no-decoration">
                            <div class="ml-2" style="line-height: 1.2;">
                                <span class="d-block p-0">{{ $contract['title'] }}</span>
                                <small class="text-muted">
                                    @if ($contract['is_expired'])
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        {{ (int)$contract['days_remaining'] }} days remaining
                                    @endif
                                </small>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">@lang('No contracts found.')</p>
        @endif
    </div>
</div>