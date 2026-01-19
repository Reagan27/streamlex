@extends('layouts.app')

@section('page-title', __('Assign Assets'))
@section('page-heading', __('Assign Assets'))

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="assign-assets-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search for users...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('assets.assign') }}"
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

                <div class="col-md-2 mt-2 mt-md-0">
                    <select name="status" id="status" class="form-control input-solid">
                        @foreach($statuses as $key => $value)
                            <option value="{{ $key }}" {{ Request::get('status') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    <th class="min-width-150">@lang('Full Name')</th>
                    <th class="min-width-150">@lang('Email')</th>
                    <th class="min-width-150">@lang('Phone')</th>
                    <th class="min-width-150">@lang('Role')</th>
                    <th class="text-center min-width-150">@lang('Action')</th>
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
                                <td class="text-center">
                                    <a href="{{ route('assets.assignAssetsPage', $user->id) }}" class="btn btn-icon" title="@lang('Assign Assets')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
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
        <!-- Pagination Links -->
        {{ $users->links() }}
    </div>
</div>

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#assign-assets-form").submit();
        });
    </script>
@stop
