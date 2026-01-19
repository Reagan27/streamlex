@extends('layouts.app')

@section('page-title', __('Assign Assets'))
@section('page-heading', __('Assign Assets'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Assign Assets')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="row mb-3 pb-3 border-bottom-light align-items-center">
            <div class="col-md-8">
                <form action="{{ route('asset.assignment.index') }}" method="GET" class="mb-0">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="@lang('Search users...')">
                        <span class="input-group-append">
                            <button class="btn btn-light" type="submit" id="search-users-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-striped table-borderless">
                <thead>
                <tr>
                    <th class="min-width-100">@lang('Name')</th>
                    <th class="min-width-100">@lang('Email')</th>
                    <th class="min-width-100">@lang('Role')</th>
                    <th class="text-center">@lang('Actions')</th>
                </tr>
                </thead>
                <tbody>
                @if (count($users))
                    @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->role->display_name }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('asset.assignment.form', $user) }}" class="btn btn-primary btn-sm">
                                    @lang('Assign Assets')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><em>@lang('No records found.')</em></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop