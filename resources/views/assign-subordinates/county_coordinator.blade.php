@extends('layouts.app')

@section('page-title', __('Assign Subordinates'))
@section('page-heading', __('Assign Subordinates'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="">
<div class="card">
<div class="card-body">
    <h2>Assign Subordinates - County Coordinator View</h2>

    <form action="{{ route('assign-subordinates.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="role" id="role-filter" class="form-control">
                    <option value="">All Roles</option>
                    @foreach($roles as $roleId => $roleName)
                        <option value="{{ $roleId }}" {{ request('role') == $roleId ? 'selected' : '' }}>
                            {{ $roleName }}
                        </option>
                    @endforeach
                </select>
            </div>
     
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <h4>Assign Quality Assurance Officer to Data Verification Officer</h4>
    <form action="{{ route('assign-subordinates.assign-field-officers') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <select name="supervisor_id" id="supervisor-select" class="form-control" required>
                    <option value="">Select Supervisor</option>
                    @foreach($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}">{{ $supervisor->first_name }} {{ $supervisor->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="field_officer_ids[]" id="field-officers-select" class="form-control" multiple required>
                    @foreach($filteredFieldOfficers as $fieldOfficer)
                        @if(!$fieldOfficer->supervisor_id)
                            <option value="{{ $fieldOfficer->id }}">{{ $fieldOfficer->first_name }} {{ $fieldOfficer->last_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Assign Field Officers</button>
            </div>
        </div>
    </form>

    @if($users->count() > 0)
    
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Supervisor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>
                                @if($user->role->name == 'Field_Officer')
                                    {{ $user->supervisor ? $user->supervisor->first_name . ' ' . $user->supervisor->last_name : 'Not Assigned' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                @if($user->role->name == 'Field_Officer' && $user->supervisor)
                                    <form action="{{ route('assign-subordinates.unassign-field-officer', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to unassign this field officer?')">Unassign</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $users->links() }}
    @else
        <p>No users found.</p>
    @endif
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#role-filter').select2({
            placeholder: 'Select a role',
            allowClear: true
        });

        $('#supervisor-select').select2({
            placeholder: 'Select a supervisor',
            allowClear: true
        });

        $('#field-officers-select').select2({
            placeholder: 'Select field officers',
            allowClear: true
        });
    });
    </script>
@endsection