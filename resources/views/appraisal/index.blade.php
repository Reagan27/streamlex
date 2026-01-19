@extends('layouts.app')

@section('page-title', __('Appraisals'))
@section('page-heading', __('Appraisals'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Appraisals')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <form action="{{ route('appraisals.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control"
                               name="search"
                               id="search-input"
                               value="{{ request('search') }}"
                               placeholder="@lang('Search by name or email')">
                        <span class="input-group-append">
                            @if (request()->has('search') && request('search') != '')
                                <a href="{{ route('appraisals.index') }}"
                                   class="btn btn-light d-flex align-items-center text-muted"
                                   role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-users-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>
                @if(auth()->user()->role->name === 'Regional_Coordinator' && $assignedCounties->isNotEmpty())
                    <div class="col-md-4">
                        <select name="county" class="form-control">
                            <option value="">@lang('All Assigned Counties')</option>
                            @foreach($assignedCounties as $id => $name)
                                <option value="{{ $id }}" {{ request('county') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">@lang('Filter')</button>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th class="min-width-150">@lang('Full Name')</th>
                        <th class="min-width-100">@lang('Email')</th>
                        @if(auth()->user()->role->name !== 'Supervisor')
                            <th class="min-width-100">@lang('County')</th>
                            <th class="min-width-100">@lang('Role')</th>
                        @endif
                        <th class="min-width-80">@lang('Appraisals Completed')</th>
                        <th class="text-center min-width-200">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($users))
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                @if(auth()->user()->role->name !== 'Supervisor')
                                    <td>{{ $user->county->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($user->role)
                                            {{ $user->role->display_name }}
                                        @else
                                            Unassigned
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    {{ $user->appraisals->where('status', true)->count() }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('appraisals.create', $user) }}" class="btn btn-icon"
                                       title="@lang('Create Appraisal')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="{{ route('appraisals.history', $user) }}" class="btn btn-icon"
                                       title="@lang('View Appraisal History')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ auth()->user()->role->name !== 'Supervisor' ? 6 : 4 }}"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $users->appends(request()->except('page'))->render() !!}

@stop