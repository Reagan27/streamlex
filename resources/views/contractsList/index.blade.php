@extends('layouts.app')

@section('page-title', __('Contracts List'))
@section('page-heading', __('Contracts List'))

@section('styles')
<style>
    .badge {
        font-size: 0.8em;
        padding: 0.35em 0.65em;
    }
    .badge-draft { background-color: #6c757d; color: white; }
    .badge-approved { background-color: #28a745; color: white; }
    .badge-declined { background-color: #dc3545; color: white; }
    .badge-terminated { background-color: #dc3545; color: white; }
    .badge-inactive { background-color: #6c757d; color: white; }
    
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

@extends('layouts.app')

@section('page-title', __('Contracts List'))

@section('page-heading')
    @lang('Contracts List')
    @if(request('project_id'))
        <small class="text-muted ml-2">
            — {{ \Vanguard\Projects::find(request('project_id'))?->name ?? __('Unknown Project') }}
        </small>
    @endif
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active">@lang('Contracts')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <!-- Search and Filters -->
            <form action="{{ route('contractsList.index') }}" method="GET" id="searchForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="@lang('Search for contracts...')">
                            <div class="input-group-append">
                                @if(request()->has('search') && request('search') != '')
                                    <a href="{{ route('contractsList.index') }}"
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

                <!-- Your existing filter section (county, role, status, date_range) -->
                <div class="filter-section {{ request()->hasAny(['county', 'role', 'status', 'date_range']) ? 'show' : '' }}">
                    <!-- ... keep all your existing filter rows ... -->
                </div>
            </form>

            <!-- Results Table -->
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('Role')</th>
                        <th>@lang('County')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Start Date')</th>
                        <th>@lang('End Date')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($contracts))
                        @foreach ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->user->first_name . ' ' . $contract->user->last_name }}</td>
                                <td>{{ $contract->user->role->display_name }}</td>
                                <td>{{ $contract->user->county->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $contract->status }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </td>
                                <td>{{ $contract->created_at->format(config('app.date_format')) }}</td>
                                <td>{{ $contract->end_date ? $contract->end_date->format(config('app.date_format')) : 'N/A' }}</td>
                                <td class="text-center">
                                    <!-- Your existing action buttons -->
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
                {{ $contracts->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

    <!-- Your modals -->
    <div class="modal fade" id="viewContractModal">...</div>
    @include('contracts.partials.modals')
@endsection
@section('scripts')
<script>
$(function() {

        $("#status, #county, #project").change(function () {
        $("#users-form").submit();
    });
    // Toggle filter section
    $('.filter-toggle').click(function() {
        $(this).toggleClass('active');
        $('.filter-section').toggleClass('show');
    });

    // Auto-submit form when filters change
    $('.filter-section select').change(function() {
        $('#searchForm').submit();
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle contract viewing
    $('.view-contract').click(function() {
        var contractId = $(this).data('contract-id');
        var viewUrl = '{{ route("contractsList.view", ":id") }}'.replace(':id', contractId);
        $('#contractFrame').attr('src', 'about:blank').attr('src', viewUrl);
    });

    $('#print-contract').click(function() {
        var iframe = document.getElementById('contractFrame');
        iframe.contentWindow.print();
    });

    // Clear iframe when modal is closed
    $('#viewContractModal').on('hidden.bs.modal', function () {
        $('#contractFrame').attr('src', 'about:blank');
    });

    // Handle contract termination
    $('.terminate-contract').click(function() {
        var contractId = $(this).data('contract-id');
        var terminateUrl = '{{ url("contracts") }}/' + contractId + '/terminate';
        $('#terminateForm').attr('action', terminateUrl);
    });

    // Handle user reset
    $('.reset-user').click(function() {
        var contractId = $(this).data('contract-id');
        var resetUrl = '{{ url("contracts") }}/' + contractId + '/reset';
        $('#resetForm').attr('action', resetUrl);
    });
});
</script>
@endsection