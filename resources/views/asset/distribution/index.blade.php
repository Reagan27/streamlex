@extends('layouts.app')

@section('page-title', __('Distribute Assets'))
@section('page-heading', __('Distribute Assets'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Distribute Assets')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('asset.distribution.index') }}" method="GET" class="mb-0">
                        <div class="input-group custom-search-form">
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   id="search-input"
                                   value="{{ request('search') }}"
                                   placeholder="@lang('Search users...')">
                            <span class="input-group-append">
                                @if (request()->has('search') && request('search') != '')
                                    <a href="{{ route('asset.distribution.index') }}"
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
                        @if ($users->count())
                            @foreach ($users as $user)
                                <tr>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->email }}</td>
                                    <td class="align-middle">{{ $user->role->display_name }}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('asset.distribution.form', $user) }}" class="btn btn-primary">
                                            @lang('Distribute')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    <em>@lang('No records found.')</em>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $users->links() }}
@stop
