@extends('layouts.app')

@section('page-title', __('Asset Details'))
@section('page-heading', __('Asset Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('assets.management') }}">@lang('Assets')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Asset Details')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <table class="table table-borderless table-striped">
            <tr>
                <th>@lang('Asset Name')</th>
                <td>{{ $asset->name }}</td>
            </tr>
            <tr>
                <th>@lang('Category')</th>
                <td>{{ ucfirst($asset->category) }}</td>
            </tr>
            <tr>
                <th>@lang('Status')</th>
                <td>{{ ucfirst($asset->status) }}</td>
            </tr>
            <tr>
                <th>@lang('Serial Number')</th>
                <td>{{ $asset->serial_number }}</td>
            </tr>
            <tr>
                <th>@lang('IMEI Number')</th>
                <td>{{ $asset->imei_number }}</td>
            </tr>
            <tr>
                <th>@lang('Number of Items')</th>
                <td>{{ $asset->number_of_items }}</td>
            </tr>
        </table>

        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
            @lang('Edit Asset')
        </a>

        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('@lang('Are you sure you want to delete this asset?')')">
                @lang('Delete Asset')
            </button>
        </form>
    </div>
</div>

@stop
