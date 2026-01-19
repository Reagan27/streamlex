@php
    $isAdmin = auth()->user()->isAdmin() || auth()->user()->hasRole('Manager');
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Durable Assets Overview</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Asset</th>
                        @if($isAdmin)
                            <th>Total</th>
                            <th>Assigned</th>
                            <th>Distributed</th>
                            <th>Available</th>
                        @elseif(in_array($userRole, ['Regional_Coordinator', 'County_Coordinator', 'Supervisor']))
                            <th>Total Inventory</th>
                            <th>Users with Inventory</th>
                        @else
                            <th>In My Inventory</th>
                            <th>Assigned to Me</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($durableAssets as $asset)
                        <tr>
                            <td>{{ $asset->name }}</td>
                            @if($isAdmin)
                                <td>{{ $asset->total_quantity }}</td>
                                <td>{{ $asset->assigned_count }}</td>
                                <td>{{ $asset->distributed_quantity ?? 0 }}</td>
                                <td>{{ $asset->available_quantity }}</td>
                            @elseif(in_array($userRole, ['Regional_Coordinator', 'County_Coordinator', 'Supervisor']))
                                <td>{{ $asset->total_inventory }}</td>
                                <td>{{ $asset->users_with_inventory }}</td>
                            @else
                                <td>{{ $asset->inventory_quantity }}</td>
                                <td>{{ $asset->assigned_to_me }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>