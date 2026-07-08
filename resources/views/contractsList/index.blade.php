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

                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Project')</label>
                                <select name="project_id" class="form-control">
                                    <option value="">@lang('All Projects')</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control">
                                    <option value="">@lang('All Statuses')</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        @lang('Approved')
                                    </option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                        @lang('Accepted')
                                    </option>
                                    <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>
                                        @lang('Declined')
                                    </option>
                                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>
                                        @lang('Terminated')
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        @lang('Inactive')
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Date Range')</label>
                                <select name="date_range" class="form-control">
                                    <option value="">@lang('All Time')</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>
                                        @lang('Today')
                                    </option>
                                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>
                                        @lang('This Week')
                                    </option>
                                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>
                                        @lang('This Month')
                                    </option>
                                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>
                                        @lang('This Year')
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
                            @if(request()->hasAny(['county', 'role', 'status', 'date_range']))
                                <a href="{{ route('contractsList.index') }}" class="btn btn-light ml-2">
                                    <i class="fas fa-times mr-1"></i> @lang('Clear Filters')
                                </a>
                            @endif
                        </div>
                    </div>
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
                        @forelse ($contracts as $contract)
                            @php $signatures = $contract->userContractSignatures ?? collect(); @endphp
                            @if($signatures && $signatures->count())
                                @foreach($signatures as $signature)
                                    <tr>
                                        <td>{{ optional($signature->user)->first_name ? $signature->user->first_name . ' ' . $signature->user->last_name : 'N/A' }}</td>
                                        <td>{{ optional(optional($signature->user)->role)->display_name ?? 'N/A' }}</td>
                                        <td>{{ optional(optional($signature->user)->county)->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $signature->status }}">
                                                {{ ucfirst($signature->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $contract->start_date ? (is_string($contract->start_date) ? \Carbon\Carbon::parse($contract->start_date)->format(config('app.date_format')) : $contract->start_date->format(config('app.date_format'))) : 'N/A' }}</td>
                                        <td>{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format(config('app.date_format')) : 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('contracts.audit-trail', ['contract' => $contract->id]) }}"
                                                    class="btn btn-icon"
                                                    data-toggle="tooltip"
                                                    title="@lang('View Audit Trail')">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-icon view-contract"
                                                        data-contract-id="{{ $signature->id }}"
                                                        data-toggle="modal"
                                                        data-target="#viewContractModal"
                                                        title="@lang('View Contract')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if(in_array($signature->status, ['approved', 'accepted']))
                                                    <button type="button"
                                                            class="btn btn-icon text-danger terminate-contract"
                                                            data-contract-id="{{ $signature->id }}"
                                                            data-toggle="modal"
                                                            data-target="#terminateModal"
                                                            title="@lang('Terminate Contract')">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @endif
                                                @if(in_array($signature->status, ['terminated', 'expired', 'inactive']))
                                                    <button type="button"
                                                            class="btn btn-icon text-warning reset-user"
                                                            data-contract-id="{{ $signature->id }}"
                                                            data-toggle="modal"
                                                            data-target="#resetModal"
                                                            title="@lang('Reset User Status')">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3"><em>@lang('Not assigned')</em></td>
                                    <td>
                                        <span class="badge badge-{{ $contract->status }}">
                                            {{ ucfirst($contract->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $contract->start_date ? (is_string($contract->start_date) ? \Carbon\Carbon::parse($contract->start_date)->format(config('app.date_format')) : $contract->start_date->format(config('app.date_format'))) : 'N/A' }}</td>
                                    <td>{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format(config('app.date_format')) : 'N/A' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('contracts.audit-trail', ['contract' => $contract->id]) }}"
                                                class="btn btn-icon"
                                                data-toggle="tooltip"
                                                title="@lang('View Audit Trail')">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-icon view-contract"
                                                    data-contract-id="{{ $contract->id }}"
                                                    data-toggle="modal"
                                                    data-target="#viewContractModal"
                                                    title="@lang('View Contract')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <em>@lang('No records found.')</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $contracts->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

    
<div class="modal fade" id="viewContractModal" tabindex="-1" aria-labelledby="viewContractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContractModalLabel">@lang('View Contract')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="contractFrame" class="contract-viewer" src="" style="width: 100%; height: 700px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn-primary" id="print-contract">
                    <i class="fas fa-print mr-1"></i> @lang('Print Contract')
                </button>
            </div>
        </div>
    </div>
</div>

    @include('contracts.partials.modals')
@endsection

@section('scripts')
<script>
$(function() {
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
        var terminateUrl = '{{ route('contracts.terminate', ['id' => ':id']) }}'.replace(':id', contractId);
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