<?php if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager')): ?>
@extends('layouts.app')

@section('page-title', __('Contracts'))
@section('page-heading', __('Contracts'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Contracts')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('contracts.index') }}" method="GET" class="mb-0">
                        <div class="input-group custom-search-form">
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   id="search-input"
                                   value="{{ request('search') }}"
                                   placeholder="@lang('Search for contracts...')">
                            <span class="input-group-append">
                                @if (request()->has('search') && request('search') != '')
                                    <a href="{{ route('contracts.index') }}"
                                       class="btn btn-light d-flex align-items-center text-muted"
                                       role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-contracts-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add Contract')
                    </a>
                </div>
            </div>

            <div class="table-responsive" id="contracts-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-150">@lang('Title')</th>
                        <th class="min-width-100">@lang('Start Date')</th>
                        <th class="min-width-100">@lang('Number of Days')</th>
                        <th class="min-width-100">@lang('Status')</th>
                        <th class="min-width-100">@lang('Active')</th>
                        <th class="min-width-100">@lang('Remaining Days')</th>
                        <th class="min-width-100">@lang('End Date')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($contracts))
                        @foreach ($contracts as $contract)
                            <tr>
                                <td class="align-middle">
                                    <a href="{{ route('contracts.show', $contract) }}">
                                        {{ $contract->title ?: __('N/A') }}
                                    </a>
                                </td>
                                <td class="align-middle">{{ $contract->start_date->format(config('app.date_format')) }}</td>
                                <td class="align-middle">{{ $contract->number_of_days }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-lg badge-{{ $contract->status == 'published' ? 'success' : ($contract->status == 'draft' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                @if($contract->active_for_onboarding)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times mr-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                                <td class="align-middle">
                                    @if($contract->remaining_days === 'Expired')
                                        <span class="badge badge-danger">Expired</span>
                                    @else
                                        {{ $contract->remaining_days }} 
                                    @endif
                                </td>
                                <td class="align-middle">{{ $contract->end_date ? $contract->end_date->format(config('app.date_format')) : __('N/A') }}</td>
                                <td class="text-center align-middle">
                                    <div class="dropdown show d-inline-block">
                                        <a class="btn btn-icon"
                                           href="#" role="button" id="dropdownMenuLink"
                                           data-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                            <a href="{{ route('contracts.show', $contract) }}" class="dropdown-item text-gray-800">
                                                <i class="fas fa-eye mr-2"></i>
                                                @lang('View Contract')
                                            </a>
                                            <a href="{{ route('contracts.edit', $contract) }}" class="dropdown-item text-gray-800">
                                                <i class="fas fa-edit mr-2"></i>
                                                @lang('Edit Contract')
                                            </a>
                                        </div>
                                    </div>

                                    <a href="{{ route('contracts.destroy', $contract) }}"
                                       class="btn btn-icon"
                                       title="@lang('Delete Contract')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('Please Confirm')"
                                       data-confirm-text="@lang('Are you sure that you want to delete this contract?')"
                                       data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
<?php endif; ?>
