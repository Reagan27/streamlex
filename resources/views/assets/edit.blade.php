@extends('layouts.app')

@section('page-title', __('Edit Asset'))
@section('page-heading', __('Edit Asset'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('assets.management') }}">@lang('Assets')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Edit Asset')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST" id="asset-form">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">@lang('Asset Name')</label>
                        <input type="text" class="form-control input-solid" id="name" name="name" value="{{ old('name', $asset->name) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">@lang('Category')</label>
                        <select name="category" id="category" class="form-control input-solid">
                            <option value="consumable" {{ old('category', $asset->category) == 'consumable' ? 'selected' : '' }}>@lang('Consumable')</option>
                            <option value="durable" {{ old('category', $asset->category) == 'durable' ? 'selected' : '' }}>@lang('Durable')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <select name="status" id="status" class="form-control input-solid">
                            <option value="new" {{ old('status', $asset->status) == 'new' ? 'selected' : '' }}>@lang('New')</option>
                            <option value="good" {{ old('status', $asset->status) == 'good' ? 'selected' : '' }}>@lang('Good')</option>
                            <option value="poor" {{ old('status', $asset->status) == 'poor' ? 'selected' : '' }}>@lang('Poor')</option>
                            <option value="damaged" {{ old('status', $asset->status) == 'damaged' ? 'selected' : '' }}>@lang('Damaged')</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_items">@lang('Number of Items')</label>
                        <input type="number" class="form-control input-solid" id="number_of_items" name="number_of_items" value="{{ old('number_of_items', $asset->number_of_items) }}" min="1">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="serial_number">@lang('Serial Number')</label>
                        <input type="text" class="form-control input-solid" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="imei_number">@lang('IMEI Number')</label>
                        <input type="text" class="form-control input-solid" id="imei_number" name="imei_number" value="{{ old('imei_number', $asset->imei_number) }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-rounded float-right">
                @lang('Update Asset')
            </button>
        </form>
    </div>
</div>

@stop
