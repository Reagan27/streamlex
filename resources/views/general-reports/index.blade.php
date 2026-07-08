@extends('layouts.app')

@section('page-title', __('General Reports'))
@section('page-heading', __('General Reports'))

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <!-- Search and Filters -->
            <div class="mb-4">
                <form action="{{ route('general-reports.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by title...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="daily" {{ request('category') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ request('category') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ request('category') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ request('category') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ request('category') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="occurance" {{ request('category') == 'occurance' ? 'selected' : '' }}>Occurance</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="county" class="form-select" onchange="this.form.submit()">
                            <option value="">All Counties</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" 
                                        {{ request('county') == $county->id ? 'selected' : '' }}>
                                    {{ $county->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>

                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('general-reports.dashboard') }}" class="btn btn-outline-primary rounded-pill me-2">
                            <i class="fas fa-chart-bar me-2"></i>Overview
                        </a>
                        <a href="{{ route('general-reports.create') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-plus me-2"></i>New Report
                        </a>
                    </div>
                </form>
            </div>

            <!-- Reports Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>County</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Files</th>
                            <th>Date Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <a href="{{ route('general-reports.show', $report) }}" 
                                       class="text-decoration-none">
                                        {{ Str::limit($report->title, 40) }}
                                    </a>
                                </td>
                                <td>{{ ucfirst($report->category) }}</td>
                                <td>{{ ucfirst(str_replace(['_', '-'], ' ', $report->subcategory)) ?? 'N/A' }}</td>
                                <td>{{ $report->county->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $report->creator->name }}
                                    <br>
                                    <small class="text-muted">{{ $report->creator->role->display_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $report->status === 'draft' ? 'secondary' : ($report->status === 'submitted' ? 'primary' : 'success') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $report->attachments_count }}
                                    </span>
                                </td>
                                <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('general-reports.show', $report) }}" 
                                           class="btn btn-sm btn-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($report->status !== 'approved')
                                            @if($report->created_by === auth()->id() || auth()->user()->hasRole(['Admin', 'Manager']))
                                                <a href="{{ route('general-reports.edit', $report) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit Report">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('general-reports.destroy', $report) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this report?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Report">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($report->status === 'submitted' && $report->can_approve)
                                                <form action="{{ route('general-reports.approve', $report) }}" 
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success"
                                                            title="Approve Report">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-alt fa-2x mb-3"></i>
                                        <p class="mb-0">No reports found</p>
                                        @if(request()->hasAny(['search', 'county', 'status', 'category']))
                                            <div class="mt-2">
                                                <a href="{{ route('general-reports.index') }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Clear Filters
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $reports->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
