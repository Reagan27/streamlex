@extends('layouts.app')

@section('page-title', __('Single Asset Assignment to ') . $user->full_name)
@section('page-heading', __('Assign Assets to ') . $user->full_name)

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-12">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="assetTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="assign-tab" data-toggle="tab" href="#assignAssets" role="tab" aria-controls="assign" aria-selected="true">
                    @lang('Assign Assets')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="assigned-tab" data-toggle="tab" href="#assignedAssets" role="tab" aria-controls="assigned" aria-selected="false">
                    @lang('Assigned Assets')
                </a>
            </li>
        </ul>

        <div class="tab-content" id="assetTabContent">
            <!-- Assign Assets Tab -->
            <div class="tab-pane fade show active" id="assignAssets" role="tabpanel" aria-labelledby="assign-tab">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('assets.assignToUser', $user->id) }}" method="POST" id="assign-form">
                                    @csrf
                                    <div class="table-responsive" id="assets-table-wrapper">
                                        <table class="table table-borderless table-striped">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Select')</th>
                                                    <th>@lang('Asset Name')</th>
                                                    <th>@lang('Category')</th>
                                                    <th>@lang('Status')</th>
                                                    <th>@lang('Serial Number')</th>
                                                    <th>@lang('IMEI Number')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($assets) && count($assets) > 0)
                                                    @foreach ($assets as $asset)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" 
                                                                       name="asset_ids[]" 
                                                                       value="{{ $asset->id }}" 
                                                                       class="asset-checkbox"
                                                                       @if(in_array($asset->id, $assignedAssetIds)) 
                                                                           disabled 
                                                                       @endif>
                                                            </td>
                                                            <td>{{ $asset->name }}</td>
                                                            <td>{{ ucfirst($asset->category) }}</td>
                                                            <td>{{ ucfirst($asset->status) }}</td>
                                                            @if($asset->category == 'durable')
                                                                <td>
                                                                    <input type="text" 
                                                                           name="serial_number[{{ $asset->id }}]" 
                                                                           class="form-control" 
                                                                           placeholder="Enter Serial Number (Optional)"
                                                                           @if(in_array($asset->id, $assignedAssetIds)) 
                                                                               disabled 
                                                                           @endif>
                                                                </td>
                                                                <td>
                                                                    <input type="text" 
                                                                           name="imei_number[{{ $asset->id }}]" 
                                                                           class="form-control" 
                                                                           placeholder="Enter IMEI Number (Optional)"
                                                                           @if(in_array($asset->id, $assignedAssetIds)) 
                                                                               disabled 
                                                                           @endif>
                                                                </td>
                                                            @else
                                                                <td colspan="2">@lang('N/A')</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7">
                                                            <em>@lang('No available assets found.')</em>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="assign-btn" disabled>@lang('Assign Selected Assets')</button>
                                </form>                                                                                                               
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar showing remaining assets -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>
                                    @lang('Assets Register')
                                </h4>
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
            <div class="tab-pane fade" id="assignedAssets" role="tabpanel" aria-labelledby="assigned-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('assets.return', ['id' => $asset->id]) }}" method="POST" id="return-form">
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
                                                    <th>@lang('Action')</th>
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
                                                                <td>
                                                                    @if($assignedAsset->assigned_by == auth()->id())
                                                                        <em>@lang('Eligible for return')</em>
                                                                    @else
                                                                        <em>@lang('Return not allowed')</em>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7"><em>@lang('No single assignment assets assigned to this user.')</em></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-warning" id="return-btn" disabled>@lang('Return Selected Items')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.asset-checkbox');
    const assignBtn = document.getElementById('assign-btn');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            toggleAssignButton();
        });
    });

    function toggleAssignButton() {
        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        assignBtn.disabled = !anyChecked;
    }

    const returnCheckboxes = document.querySelectorAll('.return-checkbox');
    const returnBtn = document.getElementById('return-btn');

    returnCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            toggleReturnButton();
        });
    });

    function toggleReturnButton() {
        const anyChecked = Array.from(returnCheckboxes).some(checkbox => checkbox.checked);
        returnBtn.disabled = !anyChecked;
    }
});
</script>
@stop
