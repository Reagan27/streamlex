@extends('layouts.app')

@section('page-title', __('Contract Approval'))
@section('page-heading', __('Contract Approval'))

@section('styles')
<style>
    .filter-section {
        display: none;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
    .filter-section.show {
        display: block;
    }
    .filter-toggle {
        cursor: pointer;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-toggle i {
        transition: transform 0.2s;
    }
    .filter-toggle.active i {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <!-- Search and Filters -->
            <div class="mb-4">
                <form action="{{ route('approval.index') }}" method="GET" id="searchForm">
                    <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="@lang('Search by name, email or role...')">
                                <div class="input-group-append">
                                    @if(request()->has('search') && request('search') != '')
                                        <a href="{{ route('approval.index', request()->except('search')) }}" 
                                           class="btn btn-light" 
                                           data-toggle="tooltip" 
                                           title="@lang('Clear Search')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-light" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" 
                                    class="filter-toggle btn btn-light" 
                                    data-toggle="tooltip" 
                                    title="@lang('Toggle Filters')">
                                <i class="fas fa-filter"></i> @lang('Filters')
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="filter-section {{ request()->hasAny(['county', 'role', 'status']) ? 'show' : '' }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('County')</label>
                                    <select name="county" class="form-control">
                                        <option value="">@lang('All Counties')</option>
                                        @foreach($counties as $county)
                                            <option value="{{ $county->id }}" 
                                                    {{ request('county') == $county->id ? 'selected' : '' }}>
                                                {{ $county->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Role')</label>
                                    <select name="role" class="form-control">
                                        <option value="">@lang('All Roles')</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" 
                                                    {{ request('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <select name="status" class="form-control">
                                        <option value="">@lang('All Statuses')</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                            @lang('Draft')
                                        </option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                            @lang('Approved')
                                        </option>
                                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                            @lang('Accepted')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> @lang('Apply Filters')
                                </button>
                                @if(request()->hasAny(['county', 'role', 'status']))
                                    <a href="{{ route('approval.index', ['page' => request()->get('page', 1)]) }}" 
                                       class="btn btn-light ml-2">
                                        <i class="fas fa-times mr-1"></i> @lang('Clear Filters')
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Table -->
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('Full Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Role')</th>
                            <th>@lang('County')</th>
                            <th>@lang('Contract Signed Date')</th>
                            <th>@lang('Status')</th>
                            <th class="text-center">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users))
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->display_name }}</td>
                                    <td>{{ $user->county->name ?? 'N/A' }}</td>
                                    <td>{{ $user->contractSignature->agreed_at ? 
                                          $user->contractSignature->agreed_at->format(config('app.date_format')) : 
                                          __('N/A') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->contractSignature->status }}">
                                            {{ ucfirst($user->contractSignature->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($canApprove || ($canAccept && $user->contractSignature->status === 'approved'))
                                            <a href="{{ route('approval.show', [
                                                    $user->id, 
                                                    'page' => request()->get('page', 1),
                                                    'search' => request('search'),
                                                    'county' => request('county'),
                                                    'role' => request('role'),
                                                    'status' => request('status')
                                                ]) }}" 
                                               class="btn btn-info btn-sm">
                                                @if ($canAccept && $user->contractSignature->status === 'approved')
                                                    <i class="fas fa-check mr-1"></i> @lang('Accept')
                                                @else
                                                    <i class="fas fa-eye mr-1"></i> @lang('View Details')
                                                @endif
                                            </a>
                                        @else
                                            <span class="text-muted">@lang('No Action')</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">
                                    <em>@lang('No records found.')</em>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(function() {
    // Preserve the current page when submitting the search form
    $('#searchForm').submit(function() {
        // Get the current page from the URL
        let currentPage = new URLSearchParams(window.location.search).get('page') || 1;
        // Add it as a hidden field
        $(this).find('input[name="page"]').val(currentPage);
    });

    $('.filter-toggle').click(function() {
        $(this).toggleClass('active');
        $('.filter-section').toggleClass('show');
    });

    $('.filter-section select').change(function() {
        $('#searchForm').submit();
    });
});
</script>
@endsection