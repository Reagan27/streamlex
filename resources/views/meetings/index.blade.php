@extends('layouts.app')

@section('page-title', 'Meetings')

@section('styles')
<style>
    .meeting-card {
        transition: box-shadow 0.3s ease;
        border: 1px solid #e0e0e0;
    }
    .meeting-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.25rem;
    }
    .type-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-calendar-alt me-2"></i>Meetings</h2>
        </div>
        <div>
            <a href="{{ route('meetings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Meeting
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('meetings.index') }}" method="get" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search meetings..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="all">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="all">All Types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Meetings List -->
    @if ($meetings->count() > 0)
        <div class="row">
            @foreach ($meetings as $meeting)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card meeting-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $meeting->title }}</h5>
                                <span class="status-badge badge bg-{{ $meeting->status == 'Completed' ? 'success' : ($meeting->status == 'Scheduled' ? 'info' : ($meeting->status == 'In Progress' ? 'warning' : 'secondary')) }}">
                                    {{ $meeting->status }}
                                </span>
                            </div>
                            
                            <div class="small text-muted mb-3">
                                <div class="mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $meeting->meeting_date->format('M d, Y') }}
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ $meeting->start_time }} - {{ $meeting->end_time }}
                                </div>
                                <div class="mb-2">
                                    <span class="type-badge badge bg-light text-dark">
                                        {{ $meeting->meeting_type }}
                                    </span>
                                </div>
                            </div>

                            @if ($meeting->meeting_type == 'Physical')
                                <div class="small text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    {{ $meeting->venue_name }}
                                </div>
                            @else
                                <div class="small text-muted mb-3">
                                    <i class="fas fa-video me-2"></i>
                                    {{ $meeting->meeting_platform }}
                                </div>
                            @endif

                            <div class="small mb-3">
                                <div class="text-muted">
                                    <i class="fas fa-users me-2"></i>
                                    {{ $meeting->participants_count }} Participants
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-tasks me-2"></i>
                                    {{ $meeting->actions_count }} Actions
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('meetings.show', $meeting->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $meetings->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>No meetings found. <a href="{{ route('meetings.create') }}">Create one now!</a>
        </div>
    @endif
</div>
@endsection
