@extends('layouts.app')

@section('page-title', __('Add User'))
@section('page-heading', __('Create New User'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">@lang('Users')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Create')
    </li>
@stop

@section('content')

@include('partials.messages')

<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="user-form">
    @csrf
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">@lang('User Details')</h5>
            
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
    <label for="role_id">@lang('Role')</label>
    <select name="role_id" id="role_id" class="form-control input-solid">
        <option value="">@lang('Select a Role')</option>
        @foreach($roles as $role)
            <option value="{{ $role['id'] }}" data-role-name="{{ $role['name'] }}">{{ $role['display_name'] }}</option>
        @endforeach
    </select>
</div>

        <div class="form-group">
            <label for="projects">@lang('Assign Projects')</label>
            <select name="projects[]" id="projects" class="form-control input-solid" multiple style="width: 100%;">
                @foreach(Vanguard\Projects::all() as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                Hold Ctrl/Cmd to select multiple select. First selected = active project.
            </small>
        </div>

        <div class="form-group">
            <label for="active_project_id">@lang('Active Project')</label>
            <select name="active_project_id" id="active_project_id" class="form-control input-solid">
                <option value="">@lang('Auto-select first project')</option>
                @foreach(Vanguard\Projects::all() as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                This will be the user's default project after login.
            </small>
        </div>

                    <!-- Regional Coordinator: Multi-county selection -->
                    <div id="regional-coordinator-section" class="form-group" style="display: none;">
                        <label for="counties" style="display: block; width: 100%;">@lang('Counties')</label>
                        <select id="counties" name="counties[]" multiple class="form-control input-solid" style="width: 100%;">
                            @foreach($counties as $countyId => $countyName)
                                <option value="{{ $countyId }}">{{ $countyName }}</option>
                            @endforeach
                        </select>
                    </div>

                   <!-- County Coordinator: Single county selection -->
<div id="county-coordinator-section" class="form-group" style="display: none;">
    <label for="county">@lang('County')</label>
    <select id="county" name="county_id" class="form-control input-solid">
        <!-- <option value="">@lang('Select a County')</option> -->
        @foreach($counties as $countyId => $countyName)
            <option value="{{ $countyId }}">{{ $countyName }}</option>
        @endforeach
    </select>
</div>

                    <!-- Supervisor: County and Subcounty selection -->
                    <div id="supervisor-section" style="display: none;">
                        <div class="form-group">
                            <label for="supervisor-county">@lang('County')</label>
                            <select id="supervisor-county" name="county_id" class="form-control input-solid">
                                <!-- <option value="">@lang('Select a County')</option> -->
                                @foreach($counties as $countyId => $countyName)
                                    <option value="{{ $countyId }}">{{ $countyName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supervisor-subcounty">@lang('Subcounty')</label>
                            <select id="supervisor-subcounty" name="subcounty_id" class="form-control input-solid">
                                <option value="">@lang('Select a Subcounty')</option>
                            </select>
                        </div>
                    </div>

                    <!-- Field Officer: County, Subcounty, and Ward selection -->
                    <div id="field-officer-section" style="display: none;">
                        <div class="form-group">
                            <label for="field-officer-county">@lang('County')</label>
                            <select id="field-officer-county" name="county_id" class="form-control input-solid">
                                <!-- <option value="">@lang('Select a County')</option> -->
                                @foreach($counties as $countyId => $countyName)
                                    <option value="{{ $countyId }}">{{ $countyName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="field-officer-subcounty">@lang('Subcounty')</label>
                            <select id="field-officer-subcounty" name="subcounty_id" class="form-control input-solid">
                                <option value="">@lang('Select a Subcounty')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="field-officer-ward">@lang('Ward')</label>
                            <select id="field-officer-ward" name="ward_id" class="form-control input-solid">
                                <option value="">@lang('Select a Ward')</option>
                            </select>
                        </div>
                    </div>

                    <!-- Other user details fields... -->
                    <div class="form-group">
                        <label for="first_name">@lang('First Name')</label>
                        <input type="text" class="form-control input-solid" id="first_name"
                               name="first_name" placeholder="@lang('First Name')" value="{{ old('first_name') }}">
                    </div>
                </div>
                
                <!-- Second column for other user details -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">@lang('Last Name')</label>
                        <input type="text" class="form-control input-solid" id="last_name"
                               name="last_name" placeholder="@lang('Last Name')" value="{{ old('last_name') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">@lang('Phone')</label>
                        <input type="text" class="form-control input-solid" id="phone"
                               name="phone" placeholder="@lang('Phone')" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">@lang('Email')</label>
                        <input type="email" class="form-control input-solid" id="email"
                               name="email" placeholder="@lang('Email')" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <select name="status" id="status" class="form-control input-solid">
                            @foreach($statuses as $statusId => $statusName)
                                <option value="{{ $statusId }}" {{ old('status') == $statusId ? 'selected' : '' }}>
                                    {{ $statusName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Details Section -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">@lang('Password Details')</h5>
            <!-- <p class="text-muted font-weight-light">@lang('Details used for authenticating with the application.')</p>
             -->
             <div class="row">
    <!-- Column 1 -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <div class="input-group">
                <input type="password" class="form-control input-solid" id="password" name="password">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Column 2 -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <div class="input-group">
                <input type="password" class="form-control input-solid" id="password_confirmation" name="password_confirmation">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">
                @lang('Create User')
            </button>
        </div>
    </div>
</form>

@stop

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#password_confirmation');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
});

$(document).ready(function () {
    // Initialize Select2 for projects
$('#projects').select2({
    placeholder: 'Select projects...',
    allowClear: true
});
    // Initialize Select2 for multi-select
    $('#counties').select2({
        allowClear: true,
        placeholder: '',
        minimumResultsForSearch: Infinity
    }).val(null).trigger('change');

    // Function to load subcounties
    function loadSubcounties(countyId, targetSubcounty) {
        if (countyId) {
            $.get('{{ route("get.subcounties") }}', { county_id: countyId }, function (data) {
                targetSubcounty.empty().append('<option value="">Select a Subcounty</option>');
                $.each(data, function (key, value) {
                    targetSubcounty.append(`<option value="${key}">${value}</option>`);
                });
            });
        } else {
            targetSubcounty.empty().append('<option value="">Select a Subcounty</option>');
        }
    }

    // Function to load wards
    function loadWards(subcountyId, targetWard) {
        if (subcountyId) {
            $.get('{{ route("get.wards") }}', { subcounty_id: subcountyId }, function (data) {
                targetWard.empty().append('<option value="">Select a Ward</option>');
                $.each(data, function (key, value) {
                    targetWard.append(`<option value="${key}">${value}</option>`);
                });
            });
        } else {
            targetWard.empty().append('<option value="">Select a Ward</option>');
        }
    }

    // Listen for role changes
    $('#role_id').change(function () {
        const selectedRole = $(this).find(':selected').data('role-name');
        
        // Hide all geolocation sections initially
        $('#regional-coordinator-section, #county-coordinator-section, #supervisor-section, #field-officer-section').hide();
        
        // Clear any previous selections
        $('#counties, #county, #supervisor-county, #supervisor-subcounty, #field-officer-county, #field-officer-subcounty, #field-officer-ward').val('').trigger('change');

        // Show relevant section based on selected role
        switch(selectedRole) {
            case 'Regional_Coordinator':
                $('#regional-coordinator-section').show();
                break;
            case 'County_Coordinator':
                $('#county-coordinator-section').show();
                break;
            case 'Supervisor':
                $('#supervisor-section').show();
                break;
            case 'Field_Officer':
                $('#field-officer-section').show();
                break;
            default:
                console.log('Unknown role:', selectedRole);
        }
    });

    // Supervisor: County change
    $('#supervisor-county').change(function () {
        loadSubcounties($(this).val(), $('#supervisor-subcounty'));
    });

    // Field Officer: County change
    $('#field-officer-county').change(function () {
        loadSubcounties($(this).val(), $('#field-officer-subcounty'));
    });

    // Field Officer: Subcounty change
    $('#field-officer-subcounty').change(function () {
        loadWards($(this).val(), $('#field-officer-ward'));
    });

    // Form submission
    $('#user-form').submit(function(e) {
        const selectedRole = $('#role_id').find(':selected').data('role-name');
        
        // Remove all hidden inputs for county_id, subcounty_id, and ward_id
        $('input[name="county_id"], input[name="subcounty_id"], input[name="ward_id"]').remove();

        // Ensure correct county_id, subcounty_id, and ward_id are submitted for each role
        switch(selectedRole) {
            case 'Regional_Coordinator':
                $('#counties').prop('disabled', false);
                console.log('Selected counties:', $('#counties').val());
                break;
            case 'County_Coordinator':
                $(this).append(`<input type="hidden" name="county_id" value="${$('#county').val()}">`);
                break;
            case 'Supervisor':
                $(this).append(`<input type="hidden" name="county_id" value="${$('#supervisor-county').val()}">`);
                $(this).append(`<input type="hidden" name="subcounty_id" value="${$('#supervisor-subcounty').val()}">`);
                break;
            case 'Field_Officer':
                $(this).append(`<input type="hidden" name="county_id" value="${$('#field-officer-county').val()}">`);
                $(this).append(`<input type="hidden" name="subcounty_id" value="${$('#field-officer-subcounty').val()}">`);
                $(this).append(`<input type="hidden" name="ward_id" value="${$('#field-officer-ward').val()}">`);
                break;
        }
    });
});

    </script>
@stop