@extends('layouts.app')

@section('page-title', __('Bulk Asset Assignment'))
@section('page-heading', __('Bulk Asset Assignment'))

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <!-- Search and Filter Form -->
        <form action="" method="GET" id="bulk-assign-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control input-solid" name="search" value="{{ Request::get('search') }}" placeholder="@lang('Search for users...')">
                        <span class="input-group-append">
                            @if (Request::has('search') && Request::get('search') != '')
                                <a href="{{ route('assets.bulk-assign') }}" class="btn btn-light d-flex align-items-center text-muted" role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit" id="search-users-btn">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    <select name="role" id="role" class="form-control input-solid">
                        @foreach($roles as $key => $value)
                            <option value="{{ $key }}" {{ Request::get('role') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Users Table -->
        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    <th>@lang('Full Name')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Phone')</th>
                    <th>@lang('Role')</th>
                    <th>@lang('Created')</th>
                    <th class="text-center">@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($users))
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ str_replace('_', ' ', $user->role->name) }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('assets.bulkAssignAssetsPage', $user->id) }}" class="btn btn-icon" title="@lang('Assign Assets')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6"><em>@lang('No eligible users found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination Links -->
        {{ $users->links() }}
    </div>
</div>

@stop
