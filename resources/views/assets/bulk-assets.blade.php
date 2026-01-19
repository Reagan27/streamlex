@extends('layouts.app')

@section('page-title', __('Bulk Asset Assignment to ') . $user->full_name)
@section('page-heading', __('Assign Assets to ') . $user->full_name)

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="bulkAssetTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="assign-bulk-tab" data-toggle="tab" href="#assignBulkAssets" role="tab" aria-controls="assign" aria-selected="true">
                    @lang('Assign Assets')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assigned-bulk-tab" data-toggle="tab" href="#assignedBulkAssets" role="tab" aria-controls="assigned" aria-selected="false">
                    @lang('Assigned Assets')
                </a>
            </li>
        </ul>

        <div class="tab-content" id="bulkAssetTabContent">
            <!-- Assign Assets Tab -->
            <div class="tab-pane fade show active" id="assignBulkAssets" role="tabpanel" aria-labelledby="assign-bulk-tab">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('assets.bulkAssignAssetsToUser', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="table-responsive" id="assets-table-wrapper">
                                        <table class="table table-borderless table-striped">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Select')</th>
                                                    <th>@lang('Asset Name')</th>
                                                    <th>@lang('Category')</th>
                                                    <th>@lang('Status')</th>
                                                    <th>@lang('Assign Quantity')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($assets) > 0)
                                                    @foreach ($assets as $asset)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" class="asset-checkbox"
                                                                {{ (auth()->user()->isAdmin() && $asset->number_of_items == 0) ? 'disabled' : '' }} 
                                                                {{ (!auth()->user()->isAdmin() && ($asset->remaining_quantity == 0 && $asset->total_received == 0)) ? 'disabled' : '' }}>
                                                            </td>
                                                            <td>{{ $asset->name }}</td>
                                                            <td>{{ ucfirst($asset->category) }}</td>
                                                            <td>{{ ucfirst($asset->status) }}</td>
                                                            <td>
                                                                <input type="number" 
                                                                       name="quantities[{{ $asset->id }}]" 
                                                                       class="form-control" 
                                                                       min="1" 
                                                                       placeholder="Enter quantity" 
                                                                       step="1" 
                                                                       {{ auth()->user()->isAdmin() ? "max=$asset->number_of_items" : "max=$asset->total_received" }}
                                                                       value="{{ old('quantities.' . $asset->id) }}" 
                                                                       {{ auth()->user()->isAdmin() && $asset->number_of_items == 0 ? 'disabled' : '' }}
                                                                       {{ !auth()->user()->isAdmin() && ($asset->remaining_quantity == 0 && $asset->total_received == 0) ? 'disabled' : '' }}>
                                                            </td>                                                                                   
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5"><em>@lang('No available assets found.')</em></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" id="assign-assets-btn" class="btn btn-primary" disabled>@lang('Assign Selected Assets')</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar showing remaining assets -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>@lang('Assets Register')</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @if(auth()->user()->isAdmin())
                                        @foreach ($assets as $asset)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $asset->name }}</strong>
                                                </div>
                                                <div>
                                                    <small class="mr-3">@lang('Total'): {{ $asset->number_of_items }}</small>
                                                    <small>@lang('Remaining'): {{ $asset->remainder }}</small>
                                                </div>
                                            </li>                                        
                                        @endforeach
                                    @else
                                        @forelse ($assets as $asset)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $asset->name }}
                                            <div>
                                                <!-- @lang('Received'): {{ $asset->total_received }} /  -->
                                                @lang('Remaining'): {{ $asset->remaining_quantity }}
                                            </div>
                                        </li>
                                        @empty
                                            <li class="list-group-item">
                                                @lang('No available assets for reassignment.')
                                            </li>
                                        @endforelse
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Assets Tab -->
            <div class="tab-pane fade" id="assignedBulkAssets" role="tabpanel" aria-labelledby="assigned-bulk-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('assets.return', $user->id) }}" method="POST">
                                    @csrf
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Select')</th>
                                                        <th>@lang('Asset Name')</th>
                                                        <th>@lang('Category')</th>
                                                        <th>@lang('Status')</th>
                                                        <th>@lang('Serial Number')</th>
                                                        <th>@lang('IMEI Number')</th>
                                                        <!-- <th>@lang('Assigned Quantity')</th>
                                                        <th>@lang('Returned Quantity')</th> -->
                                                        <th>@lang('Remaining Quantity')</th>
                                                        <th>@lang('Return Quantity')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($assignedAssets) > 0)
                                                        @foreach ($assignedAssets as $assignedAsset)
                                                            @if($assignedAsset->quantity_received != $assignedAsset->returned_quantity)
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" 
                                                                            name="assigned_asset_ids[]" 
                                                                            value="{{ $assignedAsset->id }}" 
                                                                            class="return-checkbox">
                                                                    </td>
                                                                    <td>{{ $assignedAsset->asset->name }}</td>
                                                                    <td>{{ ucfirst($assignedAsset->asset->category) }}</td>
                                                                    <td>{{ ucfirst($assignedAsset->asset->status) }}</td>
                                                                    <td>{{ $assignedAsset->serial_number }}</td>
                                                                    <td>{{ $assignedAsset->imei_number }}</td>
                                                                    <!-- <td>{{ $assignedAsset->quantity_received }}</td>
                                                                    <td>{{ $assignedAsset->returned_quantity }}</td> -->
                                                                    <td>{{ $assignedAsset->remaining_quantity }}</td>
                                                                    <td>
                                                                        <input type="number" 
                                                                            name="return_quantities[{{ $assignedAsset->id }}]" 
                                                                            class="form-control" 
                                                                            min="1" 
                                                                            max="{{ $assignedAsset->remaining_quantity }}" 
                                                                            placeholder="Enter return quantity">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10"><em>@lang('No assets assigned in bulk to this user.')</em></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>                                    
                                    <button type="submit" id="return-assets-btn" class="btn btn-warning" disabled>@lang('Return Selected Items')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const assignCheckboxes = document.querySelectorAll('.asset-checkbox');
            const returnCheckboxes = document.querySelectorAll('.return-checkbox');
            const assignBtn = document.getElementById('assign-assets-btn');
            const returnBtn = document.getElementById('return-assets-btn');

            function toggleAssignButton() {
                const anyChecked = Array.from(assignCheckboxes).some(checkbox => checkbox.checked);
                assignBtn.disabled = !anyChecked;
            }

            function toggleReturnButton() {
                const anyChecked = Array.from(returnCheckboxes).some(checkbox => checkbox.checked);
                returnBtn.disabled = !anyChecked;
            }

            assignCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleAssignButton);
            });

            returnCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleReturnButton);
            });

            toggleAssignButton();
            toggleReturnButton();
        });
    </script>
@endsection
@stop
