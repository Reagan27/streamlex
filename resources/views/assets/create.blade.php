@extends('layouts.app')

@section('page-title', __('Add Asset'))
@section('page-heading', __('Add Asset'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('assets.management') }}">@lang('Assets')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Add Asset')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST" id="asset-form">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">@lang('Asset Name')</label>
                        <input type="text" class="form-control input-solid" id="name" name="name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="category">@lang('Category')</label>
                        <select name="category" id="category" class="form-control input-solid">
                            <option value="consumable">@lang('Consumable')</option>
                            <option value="durable">@lang('Durable')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <select name="status" id="status" class="form-control input-solid">
                            <option value="new">@lang('New')</option>
                            <option value="good">@lang('Good')</option>
                            <option value="poor">@lang('Poor')</option>
                            <option value="damaged">@lang('Damaged')</option>
                        </select>
                    </div>  
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="serial_number">@lang('Serial Number')</label>
                        <input type="text" class="form-control input-solid" id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                    </div>

                    <div class="form-group">
                        <label for="imei_number">@lang('IMEI Number')</label>
                        <input type="text" class="form-control input-solid" id="imei_number" name="imei_number" value="{{ old('imei_number') }}">
                    </div>

                    <div class="form-group">
                        <label for="number_of_items">@lang('Number of Items')</label>
                        <input type="number" class="form-control input-solid" id="number_of_items" name="number_of_items" value="{{ old('number_of_items', 1) }}">
                    </div>
                </div>
            </div>
            
            @if(auth()->user()->hasRole('Admin'))
                <button type="submit" class="btn btn-primary btn-rounded float-right">
                    @lang('Add Asset')
                </button>
            @endif
        </form>
    </div>
</div>

@stop
