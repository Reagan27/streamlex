@extends('layouts.app')

@section('page-title', __('Distribute Assets'))
@section('page-heading', __('Distribute Assets to ' . $user->first_name . ' ' . $user->last_name))

@section('content')
<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            @if(strpos(session('error'), 'Transaction timeout') !== false)
                <br>The system is currently busy. Please wait a moment and try again.
            @endif
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="distribute-tab" data-toggle="tab" href="#distribute" role="tab">Distribute Assets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="return-tab" data-toggle="tab" href="#return" role="tab">Return Assets</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="distribute" role="tabpanel">
                            <form action="{{ route('asset.distribution.process', $user) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    @if($assets->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>    </th>
                                                        <th>Name</th>
                                                        <th>Available</th>
                                                        <th>Current Holdings</th>
                                                        <th>Quantity to Distribute</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($assets as $asset)
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input asset-checkbox" type="checkbox" name="assets[]" value="{{ $asset->id }}" id="asset{{ $asset->id }}" {{ old('assets') && in_array($asset->id, old('assets')) ? 'checked' : '' }}>
                                                            </td>
                                                            <td>
                                                                <label class="form-check-label" for="asset{{ $asset->id }}" style="margin-left: -20px;">{{ $asset->name }}</label>
                                                            </td>
                                                            <td>
                                                                <span>{{ $asset->quantity }}</span>
                                                            </td>
                                                            <td>
                                                               <span>{{ $userInventory->get($asset->id)?->quantity ?? 0 }}</span>
                                                           </td>
                                                            <td>
                                                                <input type="number" name="quantities[{{ $asset->id }}]" class="form-control form-control-sm quantity-input" placeholder="Qty" min="1" max="{{ $asset->quantity }}" value="{{ old('quantities.'.$asset->id) }}" {{ old('assets') && in_array($asset->id, old('assets')) ? '' : 'disabled' }}>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p>No assets available for distribution.</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="comments">Comments:</label>
                                    <textarea name="comments" id="comments" class="form-control">{{ old('comments') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Distribute Assets</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="return" role="tabpanel">
                            <form action="{{ route('asset.distribution.return', $user) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    @if($user->distributedAssets->groupBy('asset_id')->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>    </th>
                                                        <th>Name</th>
                                                        <th>Total Distributed</th>
                                                        <th>Quantity to Return</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user->distributedAssets->groupBy('asset_id') as $assetId => $distributions)
                                                        @php
                                                            $asset = $distributions->first()->asset;
                                                            $totalDistributed = $distributions->sum('quantity');
                                                            $currentHoldings = $userInventory->get($assetId)?->quantity ?? 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input asset-checkbox" type="checkbox" name="assets[]" value="{{ $assetId }}" id="return_asset{{ $assetId }}">
                                                            </td>
                                                            <td>
                                                                <label class="form-check-label" for="return_asset{{ $assetId }}" style="margin-left: -20px;">{{ $asset->name }}</label>
                                                            </td>
                                                            <td>
                                                                <span>{{ $totalDistributed }}</span>
                                                            </td>
                                                            <td>
                                                                <span>{{ $currentHoldings }}</span>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="return_quantities[{{ $assetId }}]" class="form-control form-control-sm quantity-input" placeholder="Qty" min="1" max="{{ $currentHoldings }}" disabled>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p>No assets have been distributed to this user.</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="return_comments">Comments:</label>
                                    <textarea name="return_comments" id="return_comments" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Return Assets</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Inventory Status</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($assets as $asset)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $asset->name }}
                                <span class="badge badge-primary badge-pill">{{ $asset->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.asset-checkbox').on('change', function() {
            var assetId = $(this).val();
            var quantityInput = $('input[name="quantities[' + assetId + ']"], input[name="return_quantities[' + assetId + ']"]');
            quantityInput.prop('disabled', !$(this).is(':checked'));
            if (!$(this).is(':checked')) {
                quantityInput.val('');
            }
        });
    });
</script>
@endpush