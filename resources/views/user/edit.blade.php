@extends('layouts.app')

@section('page-title', __('Edit User'))
@section('page-heading', $user->present()->nameOrEmail)

@section('styles')
@parent
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('users.index') }}">@lang('Users')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('users.edit', $user->id) }}">
        {{ $user->present()->nameOrEmail }}
    </a>
</li>
<li class="breadcrumb-item active">
    @lang('Edit')
</li>
@stop

@section('content')
@include('partials.messages')

@php
$activeTab = session('tab') ?? 'details';
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'details' ? 'active' : '' }}"
                            id="details-tab"
                            data-toggle="tab"
                            href="#details"
                            role="tab"
                            aria-controls="details"
                            aria-selected="true">
                            @lang('User Details')
                        </a>
                    </li>

                    @if($user->role->name === 'Regional_Coordinator')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'counties' ? 'active' : '' }}"
                            id="counties-tab"
                            data-toggle="tab"
                            href="#counties"
                            role="tab"
                            aria-controls="counties"
                            aria-selected="false">
                            @lang('Assigned Counties')
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}"
                            id="password-tab"
                            data-toggle="tab"
                            href="#password"
                            role="tab"
                            aria-controls="password"
                            aria-selected="false">
                            @lang('Update Password')
                        </a>
                    </li>

                    @if($canViewSensitiveInfo)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'sensitive' ? 'active' : '' }}"
                            id="sensitive-tab"
                            data-toggle="tab"
                            href="#sensitive-info"
                            role="tab"
                            aria-controls="sensitive-info"
                            aria-selected="false">
                            @lang('Sensitive Information')
                        </a>
                    </li>
                    @endif

                    @if($user->role->name === 'Field_Officer' || $user->role->name === 'Supervisor')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'team' ? 'active' : '' }}"
                            id="team-tab"
                            data-toggle="tab"
                            href="#team-info"
                            role="tab"
                            aria-controls="team-info"
                            aria-selected="false">
                            @lang('Team Info')
                        </a>
                    </li>
                    @endif
                </ul>

                <div class="tab-content mt-4" id="nav-tabContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade {{ $activeTab == 'details' ? 'show active' : '' }}" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <form action="{{ route('users.update.details', $user->id) }}" method="POST" id="details-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">@lang('First Name')</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">@lang('Last Name')</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">@lang('Phone')</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">@lang('Email')</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="role_id">@lang('Role')</label>
                                        <select name="role_id" id="role_id" class="form-control">
                                            @foreach($roles as $role)
                                            <option value="{{ $role['id'] }}"
                                                {{ $user->role_id == $role['id'] ? 'selected' : '' }}
                                                data-role-name="{{ $role['name'] }}">
                                                {{ $role['display_name'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Standard Location Fields (Hidden for Regional Coordinator) -->
                                    <div id="standard-location-fields" style="{{ $user->role->name === 'Regional_Coordinator' ? 'display: none;' : '' }}">
                                        <!-- County Selection -->
                                        <div class="form-group">
                                            <label for="county_id">@lang('County')</label>
                                            <select id="county_id" name="county_id" class="form-control">
                                                <option value="">@lang('Select a County')</option>
                                                @foreach($counties as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('county_id', $user->county_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Subcounty Selection -->
                                        <div class="form-group subcounty-field" style="{{ in_array($user->role->name, ['Supervisor', 'Field_Officer']) ? '' : 'display: none;' }}">
                                            <label for="subcounty_id">@lang('Subcounty')</label>
                                            <select id="subcounty_id" name="subcounty_id" class="form-control">
                                                <option value="">@lang('Select a Subcounty')</option>
                                                @foreach($subcounties as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('subcounty_id', $user->subcounty_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Ward Selection -->
                                        <div class="form-group ward-field" style="{{ $user->role->name === 'Field_Officer' ? '' : 'display: none;' }}">
                                            <label for="ward_id">@lang('Ward')</label>
                                            <select id="ward_id" name="ward_id" class="form-control">
                                                <option value="">@lang('Select a Ward')</option>
                                                @foreach($wards as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('ward_id', $user->ward_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">@lang('Status')</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $user->status == $status ? 'selected' : '' }}>
                                                {{ __($status) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="completed">@lang('Completed')</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="completed" name="completed" {{ $user->completed ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="completed"></label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Details')</button>
                        </form>
                    </div>

                    <!-- Regional Coordinator Counties Tab -->
                    @if($user->role->name === 'Regional_Coordinator')
                    <div class="tab-pane fade {{ $activeTab == 'counties' ? 'show active' : '' }}" id="counties" role="tabpanel" aria-labelledby="counties-tab">
                        <form action="{{ route('users.update.counties', $user->id) }}" method="POST" id="counties-form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="assigned-counties">@lang('Assigned Counties')</label>
                                <select id="assigned-counties" name="counties[]" class="form-control" multiple>
                                    @foreach($counties as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ isset($assignedCounties) && in_array($id, $assignedCounties) ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('Select multiple counties using Ctrl/Cmd + Click')</small>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Assigned Counties')</button>
                        </form>
                    </div>
                    @endif

                    <!-- Password Tab -->
                    <div class="tab-pane fade {{ $activeTab == 'password' ? 'show active' : '' }}" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('users.update.login-details', $user) }}" method="POST" id="password-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">@lang('New Password')</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">@lang('Confirm Password')</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Password')</button>
                        </form>
                    </div>

                    <!-- Sensitive Information Tab -->
                    @if($canViewSensitiveInfo)
                    <div class="mt-2 tab-pane fade {{ $activeTab == 'sensitive' ? 'show active' : '' }}" id="sensitive-info" role="tabpanel" aria-labelledby="sensitive-tab">
                        <h5 class="mb-4">@lang('Sensitive Information')</h5>

                        <!-- User Documents Form -->
                        <form action="{{ route('users.update.sensitive-info', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if($userDocuments)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_number">@lang('ID Number')</label>
                                        <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old('id_number', $userDocuments->id_number) }}">
                                    </div>
                                    <p><strong>@lang('ID Photo'):</strong> <a href="{{ asset('storage/' . $userDocuments->id_photo_path) }}" target="_blank">View ID</a></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kra_pin">@lang('KRA PIN')</label>
                                        <input type="text" class="form-control" id="kra_pin" name="kra_pin" value="{{ old('kra_pin', $userDocuments->kra_pin) }}">
                                    </div>
                                    <p><strong>@lang('KRA Certificate'):</strong> <a href="{{ asset('storage/' . $userDocuments->kra_certificate_path) }}" target="_blank">View Certificate</a></p>
                                </div>
                            </div>
                            @else
                            <p>@lang('No user documents available.')</p>
                            @endif

                            <button type="submit" class="btn btn-primary mb-4">@lang('Update Sensitive Information')</button>
                        </form>

                        <!-- Bank Details Form (Separate Form) -->
                        @can('updateBankDetails', $user)
                        <h5 class="mb-4 mt-4">@lang('Bank Details')</h5>
                        <form action="{{ route('users.update.bank-details', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_id">@lang('Bank')</label>
                                        <select class="form-control" id="bank_id" name="bank_id" required>
                                            <option value="">@lang('Select Bank')</option>
                                            @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ optional($bankDetails)->bank_id == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bank_branch">@lang('Branch')</label>
                                        <input type="text" class="form-control" id="bank_branch" name="bank_branch"
                                            value="{{ old('bank_branch', optional($bankDetails)->bank_branch) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name">@lang('Account Name')</label>
                                        <input type="text" class="form-control" id="account_name" name="account_name"
                                            value="{{ old('account_name', optional($bankDetails)->account_name) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">@lang('Account Number')</label>
                                        <input type="text" class="form-control" id="account_number" name="account_number"
                                            value="{{ old('account_number', optional($bankDetails)->account_number) }}" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Bank Details')</button>
                        </form>
                        @endcan
                    </div>
                    @endif

                    <!-- Team Info Tab -->
                    @if($user->role->name === 'Field_Officer' || $user->role->name === 'Supervisor')
                    <div class="tab-pane fade {{ $activeTab == 'team' ? 'show active' : '' }}" id="team-info" role="tabpanel" aria-labelledby="team-tab">
                        @if($user->role->name === 'Field_Officer')
                        <h4>@lang('Supervisor Information')</h4>
                        @if($user->supervisor)
                        <div class="row">
                            <div class="col-md-6">
                                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                                <p><strong>@lang('Name'):</strong>
                                    <a href="{{ route('users.edit', $user->supervisor->id) }}">
                                        {{ $user->supervisor->first_name }} {{ $user->supervisor->last_name }}
                                    </a>
                                </p>
                                @else
                                <p><strong>@lang('Name'):</strong> {{ $user->supervisor->first_name }} {{ $user->supervisor->last_name }}</p>
                                @endif
                                <p><strong>@lang('Email'):</strong> {{ $user->supervisor->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>@lang('Phone'):</strong> {{ $user->supervisor->phone }}</p>
                            </div>
                        </div>
                        @else
                        <p>@lang('No supervisor assigned.')</p>
                        @endif
                        @elseif($user->role->name === 'Supervisor')
                        <h4>@lang('Assigned Field Officers')</h4>
                        @if($user->fieldOfficers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('Phone')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->fieldOfficers as $fieldOfficer)
                                    <tr>
                                        <td>{{ $fieldOfficer->first_name }} {{ $fieldOfficer->last_name }}</td>
                                        <td>{{ $fieldOfficer->email }}</td>
                                        <td>{{ $fieldOfficer->phone }}</td>
                                        <td>
                                            <form action="{{ route('assign-subordinates.unassign-field-officer', $fieldOfficer->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('@lang('Are you sure you want to unassign this field officer?')')">
                                                    @lang('Unassign')
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p>@lang('No field officers assigned.')</p>
                        @endif
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Image Section -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ $user->present()->avatar }}" alt="{{ $user->present()->name }}" class="img-fluid rounded-circle" width="150" id="profileImage">
                    <div id="changePhotoBtn">
                        <!-- <i class="fas fa-camera text-white"></i> -->
                    </div>
                </div>
                <h4 class="card-title mt-3">{{ $user->present()->name }}</h4>
                <p class="text-muted">{{ $user->present()->email }}</p>
                <form action="{{ route('user.update.avatar', $user) }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
@parent
<script src="{{ asset('assets/js/as/profile.js') }}"></script>
{!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateDetailsRequest', '#details-form') !!}
{!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateLoginDetailsRequest', '#login-details-form') !!}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 for multiple selection
        $('#assigned-counties').select2({
            placeholder: "@lang('Select Counties')",
            allowClear: true,
            width: '100%'
        });

        // Role change handler
        $('#role_id').on('change', function() {
            const roleName = $(this).find(':selected').data('role-name');
            updateFieldVisibility(roleName);
        });

        // Initialize location fields based on current role
        const initialRole = $('#role_id').find(':selected').data('role-name');
        updateFieldVisibility(initialRole);

        // County change handler
        $('#county_id').on('change', function() {
            const countyId = $(this).val();
            if (countyId) {
                loadSubcounties(countyId);
            } else {
                $('#subcounty_id').html('<option value="">@lang("Select a Subcounty")</option>');
                $('#ward_id').html('<option value="">@lang("Select a Ward")</option>');
            }
        });

        // Subcounty change handler
        $('#subcounty_id').on('change', function() {
            const subcountyId = $(this).val();
            if (subcountyId) {
                loadWards(subcountyId);
            } else {
                $('#ward_id').html('<option value="">@lang("Select a Ward")</option>');
            }
        });

        function updateFieldVisibility(roleName) {
            // Show/hide location fields based on role
            if (roleName === 'Regional_Coordinator') {
                $('#standard-location-fields').hide();
                $('#counties-tab').show();
            } else {
                $('#standard-location-fields').show();
                $('#counties-tab').hide();

                $('.subcounty-field').toggle(
                    roleName === 'Supervisor' || roleName === 'Field_Officer'
                );

                $('.ward-field').toggle(roleName === 'Field_Officer');
            }
        }

        function loadSubcounties(countyId) {
            $.ajax({
                url: '{{ route("get.subcounties") }}',
                type: 'GET',
                data: {
                    county_id: countyId
                },
                success: function(data) {
                    let options = '<option value="">@lang("Select a Subcounty")</option>';
                    $.each(data, function(key, value) {
                        options += `<option value="${key}">${value}</option>`;
                    });
                    $('#subcounty_id').html(options);
                }
            });
        }

        function loadWards(subcountyId) {
            $.ajax({
                url: '{{ route("get.wards") }}',
                type: 'GET',
                data: {
                    subcounty_id: subcountyId
                },
                success: function(data) {
                    let options = '<option value="">@lang("Select a Ward")</option>';
                    $.each(data, function(key, value) {
                        options += `<option value="${key}">${value}</option>`;
                    });
                    $('#ward_id').html(options);
                }
            });
        }
    });
</script>
@endsection