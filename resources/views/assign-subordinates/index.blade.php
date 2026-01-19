@extends('layouts.app')

@section('page-title', __('Assign Subordinates'))
@section('page-heading', __('Assign Subordinates'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-3 pb-3 border-bottom-light">
            <div class="col-lg-12">
                <form action="{{ route('assign-subordinates.index') }}" method="GET" id="users-form">
                    <div class="row justify-content-end">
                        <div class="col-md-4 mt-2 mt-md-0">
                            <div class="input-group custom-search-form">
                                <input type="text"
                                       class="form-control input-solid"
                                       name="search"
                                       value="{{ Request::get('search') }}"
                                       placeholder="@lang('Search for users...')">
                                <span class="input-group-append">
                                    @if (Request::has('search') && Request::get('search') != '')
                                        <a href="{{ route('assign-subordinates.index') }}"
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
                    </div>
                </form>
            </div>
        </div>

        <h4>Assign Quality Assurance Officer to Data Verification Officer</h4>
<form action="{{ route('assign-subordinates.assign-field-officers') }}" method="POST" class="mb-4">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <select name="supervisor_id" id="supervisor-select" class="form-control" required>
                <option value="">Select Data Verifier</option>
                @foreach($supervisors as $supervisor)
                @if($supervisor->county)
            <option value="{{ $supervisor->id }}" data-county="{{ $supervisor->county->name }}">
                {{ $supervisor->first_name }} {{ $supervisor->last_name }} ({{ $supervisor->county->name }})
            </option>
        @else
            <option value="{{ $supervisor->id }}" data-county="N/A">
                {{ $supervisor->first_name }} {{ $supervisor->last_name }} (No County Assigned)
            </option>
        @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="field_officer_ids[]" id="field-officers-select" class="form-control" multiple required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Assign</button>
        </div>
    </div>
</form>

        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>County</th>
                            <th>Supervisor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @if($user->role->name == 'Supervisor' || $user->role->name == 'Field_Officer')
                                <tr>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->display_name }}</td>
                                    <td>{{ $user->county->name ?? 'N/A' }}</td>
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
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {!! $users->render() !!}
        @else
            <p>No users found.</p>
        @endif
    </div>
</div>
@endsection


@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#supervisor-select').select2({
            placeholder: 'Select data verifier',
            allowClear: true
        });

        $('#field-officers-select').select2({
            placeholder: 'Select quality assurance',
            allowClear: true
        });

        $('#supervisor-select').on('change', function() {
            var supervisorId = $(this).val();
            var countyName = $(this).find(':selected').data('county');
            
            if (supervisorId) {
                $.ajax({
                    url: '{{ route("get-field-officers") }}',
                    type: 'GET',
                    data: { supervisor_id: supervisorId },
                    success: function(data) {
                        $('#field-officers-select').empty();
                        $.each(data, function(key, value) {
                            $('#field-officers-select').append('<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>');
                        });
                        $('#field-officers-select').trigger('change');
                    }
                });
            } else {
                $('#field-officers-select').empty();
            }

            // Update the Field Officers select placeholder
            $('#field-officers-select').data('placeholder', 'Select Field Officers (' + countyName + ')').trigger('change');
        });
    });
    </script>
@endsection