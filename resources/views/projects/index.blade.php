@extends('layouts.app')

@section('page-title', 'Projects')
@section('page-heading', 'All Projects')

@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Projects</h5>
        @can('project.manage')
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Project
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Budget</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>{{ Str::limit($project->description, 60) }}</td>
                            <td>{{ $project->budget ? number_format($project->budget) : '-' }}</td>
                            <td>{{ $project->start_date->format('d M Y') }}</td>
                            <td>{{ $project->end_date?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $project->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $project->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">View</a>
                                @can('project.manage')
                                    <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No projects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $projects->links() }}
    </div>
</div>
@endsection
