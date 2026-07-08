@extends('layouts.app')

@section('page-title', 'Projects')
@section('page-heading', 'All Projects')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Projects</li>
@endsection

@section('content')
    @include('partials.messages')

    {{-- Active Project Display --}}
    @if(isset($currentActiveProject) && $currentActiveProject)
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-briefcase"></i> 
                <strong>Active Project:</strong> {{ $currentActiveProject->name }}
                @if($currentActiveProject->description)
                    <br><small class="text-muted">{{ Str::limit($currentActiveProject->description, 100) }}</small>
                @endif
            </div>
            <form action="{{ route('projects.clear-active') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-secondary" 
                        onclick="return confirm('Clear active project?')">
                    <i class="fas fa-times"></i> Clear
                </button>
            </form>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i> No active project selected. Select a project below to set it as active.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">Projects</h5>
                </div>
                <div class="col-md-4 text-right">
                    @if(auth()->user()->hasPermission('project.manage'))
                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Create Project')
                        </a>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-150">Name</th>
                            <th class="min-width-100">Description</th>
                            <th class="min-width-100">Budget</th>
                            <th class="min-width-100">Start Date</th>
                            <th class="min-width-100">End Date</th>
                            <th class="min-width-80">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr class="{{ (isset($currentActiveProject) && $currentActiveProject && $currentActiveProject->id == $project->id) ? 'table-primary' : '' }}">
                                <td class="align-middle">
                                    <strong>{{ $project->name }}</strong>
                                    @if(isset($currentActiveProject) && $currentActiveProject && $currentActiveProject->id == $project->id)
                                        <span class="badge badge-primary badge-pill ml-2">
                                            <i class="fas fa-check"></i> Active
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ Str::limit($project->description, 60) }}</td>
                                <td class="align-middle">{{ $project->budget ? number_format($project->budget) : '-' }}</td>
                                <td class="align-middle">{{ $project->start_date->format('d M Y') }}</td>
                                <td class="align-middle">{{ $project->end_date?->format('d M Y') ?? '-' }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-{{ $project->is_active ? 'success' : 'secondary' }} badge-pill">
                                        {{ $project->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="dropdown show d-inline-block">
                                        <a class="btn btn-icon" 
                                           href="#" 
                                           role="button" 
                                           id="dropdownMenuLink-{{ $project->id }}"
                                           data-toggle="dropdown"
                                           aria-haspopup="true" 
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-{{ $project->id }}">
                                            {{-- Set Active Option (only show if not currently active) --}}
                                            @if(!isset($currentActiveProject) || !$currentActiveProject || $currentActiveProject->id != $project->id)
                                                <a href="#" 
                                                   class="dropdown-item text-gray-800"
                                                   onclick="event.preventDefault(); document.getElementById('set-active-form-{{ $project->id }}').submit();">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    @lang('Set as Active')
                                                </a>
                                                <form id="set-active-form-{{ $project->id }}" 
                                                      action="{{ route('projects.set-active', $project->id) }}" 
                                                      method="POST" 
                                                      style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                            @endif
                                            
                                            {{-- Assign Users Option --}}
                                            @if(auth()->user()->hasPermission('project.manage'))
                                                <a href="{{ route('projects.assign-users', $project->id) }}" 
                                                   class="dropdown-item text-gray-800">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    @lang('Assign Users')
                                                </a>
                                            @endif
                                            
                                            {{-- View Option --}}
                                            <a href="#" class="dropdown-item text-gray-800">
                                                <i class="fas fa-eye mr-2"></i>
                                                @lang('View Project')
                                            </a>
                                            
                                            {{-- Edit Option --}}
                                            @if(auth()->user()->hasPermission('project.manage'))
                                                <a href="#" class="dropdown-item text-gray-800">
                                                    <i class="fas fa-edit mr-2"></i>
                                                    @lang('Edit Project')
                                                </a>
                                            @endif

                                            {{-- Delete Option --}}
                                            @if(auth()->user()->hasPermission('project.manage'))
                                                <div class="dropdown-divider"></div>
                                                <a href="#" 
                                                   class="dropdown-item text-danger"
                                                   data-method="DELETE"
                                                   data-confirm-title="@lang('Please Confirm')"
                                                   data-confirm-text="@lang('Are you sure you want to delete this project?')"
                                                   data-confirm-delete="@lang('Yes, delete it!')">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    @lang('Delete Project')
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>  
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center"><em>@lang('No projects found.')</em></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection