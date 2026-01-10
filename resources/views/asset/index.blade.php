@extends('layouts.app')

@section('page-title', __('Asset Management'))
@section('page-heading', __('Asset Management'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Assets')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('asset.index') }}" method="GET" class="mb-0">
                        <div class="input-group custom-search-form">
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   id="search-input"
                                   value="{{ request('search') }}"
                                   placeholder="@lang('Search for assets...')">
                            <span class="input-group-append">
                                @if (request()->has('search') && request('search') != '')
                                    <a href="{{ route('asset.index') }}"
                                       class="btn btn-light d-flex align-items-center text-muted"
                                       role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-assets-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('asset.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Create New Asset')
                    </a>
                </div>
            </div>

            <div class="table-responsive" id="assets-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-150">@lang('SKU Code')</th>
                        <th class="min-width-100">@lang('Name')</th>
                        <th class="min-width-100">@lang('Quantity')</th>
                        <th class="min-width-100">@lang('Category')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($assets))
                        @foreach ($assets as $asset)
                            <tr>
                                <td class="align-middle">{{ $asset->sku_code }}</td>
                                <td class="align-middle">{{ $asset->name }}</td>
                                <td class="align-middle">{{ $asset->quantity }}</td>
                                <td class="align-middle">{{ $asset->category }}</td>
                                <td class="text-center align-middle">
                                    <div class="dropdown show d-inline-block">
                                        <a class="btn btn-icon"
                                           href="#" role="button" id="dropdownMenuLink"
                                           data-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                           
                                                <a href="{{ route('asset.edit', $asset) }}" class="dropdown-item text-gray-800">
                                                    <i class="fas fa-edit mr-2"></i>
                                                    @lang('Edit Asset')
                                                </a>
                                          
                                                <a href="{{ route('asset.destroy', $asset) }}"
                                                   class="dropdown-item text-gray-800"
                                                   data-method="DELETE"
                                                   data-confirm-title="@lang('Please Confirm')"
                                                   data-confirm-text="@lang('Are you sure that you want to delete this asset?')"
                                                   data-confirm-delete="@lang('Yes, delete it!')">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    @lang('Delete Asset')
                                                </a>
                                  
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

