@extends('layouts.app')

@section('page-title', 'Assign Users to Project')
@section('page-heading', 'Assign Users: ' . $project->name)

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .user-card {
        transition: all 0.3s ease;
    }
    .user-card:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
@include('partials.messages')

<div class="row">
    <!-- Assign New Users -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus"></i> Assign Users
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store-assignments', $project) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_ids">Search and Select Users</label>
                        <select name="user_ids[]" id="user_ids" class="form-control" multiple required>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }}) - {{ $user->role->display_name ?? 'No Role' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Hold Ctrl/Cmd to select multiple users
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="set_as_active" name="set_as_active" value="1">
                            <label class="custom-control-label" for="set_as_active">
                                Set as active project for selected users
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Assign Selected Users
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Currently Assigned Users -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users"></i> Assigned Users ({{ $assignedUsers->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($assignedUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>County</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedUsers as $user)
                                    <tr>
                                        <td>
                                            {{ $user->name }}<br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>{{ $user->role->display_name ?? '-' }}</td>
                                        <td>{{ $user->county->name ?? '-' }}</td>
                                        <td>
                                            @if($user->pivot->is_active_project)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('projects.remove-user', [$project, $user]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Remove this user from the project?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">
                        No users assigned yet
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#user_ids').select2({
        placeholder: 'Search for users...',
        ajax: {
            url: '{{ route("projects.search-users", $project) }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });
});
</script>
@endsection