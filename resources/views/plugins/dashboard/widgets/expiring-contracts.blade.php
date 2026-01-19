<div class="card">
    <div class="card-header">
        Contracts Expiring Soon
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @foreach ($expiringContracts as $contract)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $contract['title'] }}</span>
                        <span class="badge {{ $contract['is_expired'] ? 'bg-danger' : 'bg-warning' }} rounded-pill">
                            @if ($contract['is_expired'])
                                Expired
                            @else
                                {{ (int)$contract['days_remaining'] }} days left
                            @endif
                        </span>
                    </div>
                    <small class="text-muted">Expires on: {{ $contract['expiry_date'] }}</small>
                </li>
            @endforeach
        </ul>
    </div>
</div>
