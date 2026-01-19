@extends('layouts.app')

@section('page-title', __('My Assets'))
@section('page-heading', __('My Assets'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('My Assets')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="assetTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab">
                    My Inventory
                </a>
            </li>    
            <li class="nav-item">
                <a class="nav-link" id="assigned-assets-tab" data-toggle="tab" href="#assigned-assets" role="tab">
                    Assigned Assets
                </a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <!-- My Inventory Tab -->
            <div class="tab-pane fade show active" id="inventory" role="tabpanel">
                @if($userInventory->count())
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Asset Name')</th>
                                    <th>@lang('SKU Code')</th>
                                    <th>@lang('Category')</th>
                                    <th class="text-center">@lang('Quantity')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userInventory as $inventory)
                                    <tr>
                                        <td>{{ $inventory->asset->name }}</td>
                                        <td>{{ $inventory->asset->sku_code }}</td>
                                        <td>{{ $inventory->asset->category }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-primary">
                                                {{ $inventory->quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        @lang('You currently have no assets in your inventory.')
                    </div>
                @endif
            </div>

            <!-- Assigned Assets Tab -->
            <div class="tab-pane fade" id="assigned-assets" role="tabpanel">
                @if($assignedAssets->count())
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Asset Name')</th>
                                    <th>@lang('SKU Code')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('IMEI Number')</th>
                                    <th>@lang('Serial Number')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Assigned Date')</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach($assignedAssets as $assignment)
        <tr>
            <td>{{ $assignment->asset->name }}</td>
            <td>{{ $assignment->asset->sku_code }}</td>
            <td>{{ $assignment->asset->category }}</td>
            <td>{{ $assignment->imei_number ?: '-' }}</td>
            <td>{{ $assignment->serial_number ?: '-' }}</td>
            <td>
                <span class="badge badge-{{ $assignment->physical_condition === 'Good' ? 'success' : ($assignment->physical_condition === 'Poor' ? 'warning' : ($assignment->physical_condition === 'Damaged' ? 'danger' : 'info')) }}">
                    {{ $assignment->physical_condition }}
                </span>
            </td>
            <td>{{ $assignment->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        @lang('You currently have no assets assigned to you.')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge-pill {
        min-width: 60px;
    }
</style>
@endpush