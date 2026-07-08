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

                {{-- ===== TAB NAV ===== --}}
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

                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'employeeinfo' ? 'active' : '' }}"
                            id="employeeinfo-tab"
                            data-toggle="tab"
                            href="#employeeinfo"
                            role="tab"
                            aria-controls="employeeinfo"
                            aria-selected="false">
                            Employee Info
                        </a>
                    </li>

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

                {{-- ===== TAB CONTENT ===== --}}
                <div class="tab-content mt-4" id="nav-tabContent">

                    {{-- ===== 1. DETAILS TAB ===== --}}
                    <div class="tab-pane fade {{ $activeTab == 'details' ? 'show active' : '' }}" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <form action="{{ route('users.update.details', $user->id) }}" method="POST" id="details-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6">
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
                                    <div class="form-group">
                                        <label for="contract_type">@lang('Contract Type')</label>
                                        <select name="contract_type" id="contract_type" class="form-control">
                                            <option value="group" {{ old('contract_type', $user->contract_type) == 'group' ? 'selected' : '' }}>@lang('Group')</option>
                                            <option value="individual" {{ old('contract_type', $user->contract_type) == 'individual' ? 'selected' : '' }}>@lang('Individual')</option>
                                        </select>
                                        <small class="form-text text-muted">@lang('Select whether this user is assigned to a group or individual contract.')</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Standard Location Fields (Hidden for Regional Coordinator) -->
                                    <div id="standard-location-fields" style="{{ $user->role->name === 'Regional_Coordinator' ? 'display: none;' : '' }}">
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
                    {{-- ===== END DETAILS TAB ===== --}}

                    {{-- ===== 2. REGIONAL COORDINATOR COUNTIES TAB ===== --}}
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
                    {{-- ===== END COUNTIES TAB ===== --}}

                    {{-- ===== 3. PASSWORD TAB ===== --}}
                    <div class="tab-pane fade {{ $activeTab == 'password' ? 'show active' : '' }}" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('users.update.login-details', $user) }}" method="POST" id="password-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6">
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
                    {{-- ===== END PASSWORD TAB ===== --}}

                    {{-- ===== 4. SENSITIVE INFORMATION TAB ===== --}}
                    @if($canViewSensitiveInfo)
                    <div class="mt-2 tab-pane fade {{ $activeTab == 'sensitive' ? 'show active' : '' }}" id="sensitive-info" role="tabpanel" aria-labelledby="sensitive-tab">
                        <h5 class="mb-4">@lang('Sensitive Information')</h5>

                        <form action="{{ route('users.update.sensitive-info', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if($userDocuments && $userDocuments->count())
                                @foreach($userDocuments as $doc)
                                <div class="row mb-4 border rounded p-2 mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_number_{{ $doc->id }}">@lang('ID Number')</label>
                                            <input type="text" class="form-control" id="id_number_{{ $doc->id }}" name="id_number[{{ $doc->id }}]" value="{{ old('id_number.' . $doc->id, $doc->id_number) }}">
                                        </div>
                                        @if($doc->id_photo_path)
                                            <p><strong>@lang('ID Photo'):</strong> <a href="{{ asset('storage/' . $doc->id_photo_path) }}" target="_blank">View ID</a></p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kra_pin_{{ $doc->id }}">@lang('KRA PIN')</label>
                                            <input type="text" class="form-control" id="kra_pin_{{ $doc->id }}" name="kra_pin[{{ $doc->id }}]" value="{{ old('kra_pin.' . $doc->id, $doc->kra_pin) }}">
                                        </div>
                                        @if($doc->kra_certificate_path)
                                            <p><strong>@lang('KRA Certificate'):</strong> <a href="{{ asset('storage/' . $doc->kra_certificate_path) }}" target="_blank">View Certificate</a></p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p>@lang('No user documents available.')</p>
                            @endif

                            <button type="submit" class="btn btn-primary mb-4">@lang('Update Sensitive Information')</button>
                        </form>

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
                    {{-- ===== END SENSITIVE INFO TAB ===== --}}

                    {{-- ===== 5. EMPLOYEE INFO TAB (single, clean copy) ===== --}}
                    <div class="tab-pane fade {{ $activeTab == 'employeeinfo' ? 'show active' : '' }}" id="employeeinfo" role="tabpanel" aria-labelledby="employeeinfo-tab">

                        {{-- Uploaded Certificates Section --}}
                        <div class="mb-4">
                            <h5 class="mb-3">Uploaded Education Certificates</h5>
                            @php
                    $hasEducationDocs = isset($user->educationDocuments) && count($user->educationDocuments) > 0;
                    $userEducationCertificates = \Vanguard\Models\UserEducationCertificate::where('user_id', $user->id)->get();
                    $hasUserEducationCertificates = $userEducationCertificates->count() > 0;
                @endphp
                @if($hasEducationDocs || $hasUserEducationCertificates)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name/Level</th>
                                            <th>Institution</th>
                                            <th>Award</th>
                                            <th>Year</th>
                                            <th>Certificate File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Show UserEducationCertificate entries --}}
                                        @foreach($userEducationCertificates as $cert)
                                        <tr>
                                            <td>{{ $cert->level }}</td>
                                            <td>{{ $cert->institution }}</td>
                                            <td>{{ $cert->award }}</td>
                                            <td>{{ $cert->year }}</td>
                                            <td>
                                                @if($cert->file_path)
                                                    <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank">View/Download</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- Show EducationDocument entries --}}
                                        @foreach($user->educationDocuments ?? [] as $doc)
                                        <tr>
                                            <td>{{ $doc->name }}</td>
                                            <td colspan="3"></td>
                                            <td>
                                                @if($doc->path)
                                                    <a href="{{ asset('storage/' . $doc->path) }}" target="_blank">View/Download</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p>No certificates uploaded.</p>
                            @endif
                        </div>

                        <form action="{{ route('profile.update.employeeinfo') }}" method="POST">
                            @csrf

                            <h5 class="mb-3">Next of Kin / Emergency Contact</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" name="nok_full_name" value="{{ old('nok_full_name', $user->nok_full_name ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Relationship</label>
                                        <input type="text" class="form-control" name="nok_relationship" value="{{ old('nok_relationship', $user->nok_relationship ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="nok_mobile" value="{{ old('nok_mobile', $user->nok_mobile ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Alternative Phone Number</label>
                                        <input type="text" class="form-control" name="nok_alt_phone" value="{{ old('nok_alt_phone', $user->nok_alt_phone ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="nok_email" value="{{ old('nok_email', $user->nok_email ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Physical Address</label>
                                        <input type="text" class="form-control" name="nok_address" value="{{ old('nok_address', $user->nok_address ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Marital &amp; Family Information</h5>
                            <div class="form-group">
                                <label>Marital Status</label><br>
                                <label><input type="radio" name="marital_status" value="single" {{ old('marital_status', $user->marital_status ?? '') == 'single' ? 'checked' : '' }}> Single</label>
                                <label><input type="radio" name="marital_status" value="married" {{ old('marital_status', $user->marital_status ?? '') == 'married' ? 'checked' : '' }}> Married</label>
                                <label><input type="radio" name="marital_status" value="divorced" {{ old('marital_status', $user->marital_status ?? '') == 'divorced' ? 'checked' : '' }}> Divorced</label>
                                <label><input type="radio" name="marital_status" value="separated" {{ old('marital_status', $user->marital_status ?? '') == 'separated' ? 'checked' : '' }}> Separated</label>
                                <label><input type="radio" name="marital_status" value="widowed" {{ old('marital_status', $user->marital_status ?? '') == 'widowed' ? 'checked' : '' }}> Widowed</label>
                            </div>
                            <div class="form-group">
                                <label>Spouse Name (if applicable)</label>
                                <input type="text" class="form-control" name="spouse_name" value="{{ old('spouse_name', $user->spouse_name ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Spouse Contact Number</label>
                                <input type="text" class="form-control" name="spouse_contact" value="{{ old('spouse_contact', $user->spouse_contact ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Number of Dependents</label>
                                <input type="number" class="form-control" name="dependents" value="{{ old('dependents', $user->dependents ?? '') }}">
                            </div>

                            <hr>
                                @php
                                    $isAdminOrManager = Auth::user()->role->name === 'Admin' || Auth::user()->role->name === 'Manager';
                                @endphp
                                @if($isAdminOrManager)
                                    <h5 class="mb-3">Employment Details</h5>
                                    <div class="form-group">
                                        <label>Employee Number</label>
                                        <input type="text" class="form-control" name="employee_number" value="{{ old('employee_number', $user->employee_number ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" class="form-control" name="department" value="{{ old('department', $user->department ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Job Title</label>
                                        <input type="text" class="form-control" name="job_title" value="{{ old('job_title', $user->job_title ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Employment Type</label><br>
                                        <label><input type="radio" name="employment_type" value="permanent" @if(old('employment_type', $user->employment_type ?? '')=='permanent') checked @endif> Permanent</label>
                                        <label><input type="radio" name="employment_type" value="contract" @if(old('employment_type', $user->employment_type ?? '')=='contract') checked @endif> Contract</label>
                                        <label><input type="radio" name="employment_type" value="parttime" @if(old('employment_type', $user->employment_type ?? '')=='parttime') checked @endif> Part-Time</label>
                                        <label><input type="radio" name="employment_type" value="casual" @if(old('employment_type', $user->employment_type ?? '')=='casual') checked @endif> Casual</label>
                                        <label><input type="radio" name="employment_type" value="intern" @if(old('employment_type', $user->employment_type ?? '')=='intern') checked @endif> Intern</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Date of Employment</label>
                                        <input type="date" class="form-control" name="employment_date" value="{{ old('employment_date', $user->employment_date ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Work Station / Duty Station</label>
                                        <input type="text" class="form-control" name="work_station" value="{{ old('work_station', $user->work_station ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Supervisor's Name</label>
                                        <input type="text" class="form-control" name="supervisor_name" value="{{ old('supervisor_name', $user->supervisor_name ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Supervisor's Title</label>
                                        <input type="text" class="form-control" name="supervisor_title" value="{{ old('supervisor_title', $user->supervisor_title ?? '') }}">
                                    </div>
                                @endif

                            <hr>
                            <h5 class="mb-3">Education &amp; Professional Qualifications</h5>
                            {{-- Education Document Upload --}}
                            <form action="{{ route('user.education.upload', $user->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <div class="form-row align-items-end">
                                    <div class="col-12 col-md-5">
                                        <label>Document Name</label>
                                        <input type="text" name="education_doc_name" class="form-control" required>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label>Upload Document</label>
                                        <input type="file" name="education_doc_file" class="form-control-file" required>
                                    </div>
                                    <div class="col-12 col-md-2 mt-2 mt-md-0">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </div>
                            </form>
                            {{-- List uploaded education documents --}}
                            <ul class="list-group mb-3">
                                @forelse($user->educationDocuments ?? [] as $doc)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $doc->name }}</span>
                                        <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                    </li>
                                @empty
                                    <li class="list-group-item">No education documents uploaded.</li>
                                @endforelse
                            </ul>

                            {{-- Other Documents Upload --}}
                            <h5 class="mb-3">Other Documents</h5>
                            <form action="{{ route('user.otherdocs.upload', $user->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <div class="form-row align-items-end">
                                    <div class="col-12 col-md-5">
                                        <label>Document Name</label>
                                        <input type="text" name="other_doc_name" class="form-control" required>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label>Upload Document</label>
                                        <input type="file" name="other_doc_file" class="form-control-file" required>
                                    </div>
                                    <div class="col-12 col-md-2 mt-2 mt-md-0">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </div>
                            </form>
                            {{-- List uploaded other documents --}}
                            <ul class="list-group mb-3">
                                @forelse($user->otherDocuments ?? [] as $doc)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $doc->name }}</span>
                                        <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                    </li>
                                @empty
                                    <li class="list-group-item">No other documents uploaded.</li>
                                @endforelse
                            </ul>
                            <div class="form-group">
                                <label>Professional Certifications</label>
                                <textarea class="form-control" name="professional_certifications">{{ old('professional_certifications', $user->professional_certifications ?? '') }}</textarea>
                            </div>

                            <hr>
                            <h5 class="mb-3">Medical Information</h5>
                            <div class="form-group">
                                <label>Blood Group (optional)</label>
                                <input type="text" class="form-control" name="blood_group" value="{{ old('blood_group', $user->blood_group ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Known Medical Conditions (optional)</label>
                                <input type="text" class="form-control" name="medical_conditions" value="{{ old('medical_conditions', $user->medical_conditions ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Allergies (if any)</label>
                                <input type="text" class="form-control" name="allergies" value="{{ old('allergies', $user->allergies ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Preferred Medical Facility</label>
                                <input type="text" class="form-control" name="medical_facility" value="{{ old('medical_facility', $user->medical_facility ?? '') }}">
                            </div>

                            <hr>
                            <h5 class="mb-3">Disability Information (Optional)</h5>
                            <div class="form-group">
                                <label>Do you have any disability?</label><br>
                                <label><input type="radio" name="disability" value="yes" {{ old('disability', $user->disability ?? '') == 'yes' ? 'checked' : '' }}> Yes</label>
                                <label><input type="radio" name="disability" value="no" {{ old('disability', $user->disability ?? '') == 'no' ? 'checked' : '' }}> No</label>
                            </div>
                            <div class="form-group">
                                <label>If yes, specify</label>
                                <input type="text" class="form-control" name="disability_details" value="{{ old('disability_details', $user->disability_details ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Workplace adjustments required</label>
                                <input type="text" class="form-control" name="workplace_adjustments" value="{{ old('workplace_adjustments', $user->workplace_adjustments ?? '') }}">
                            </div>

                            <hr>
                            <h5 class="mb-3">Statutory &amp; Compliance Declarations</h5>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_accurate" {{ old('info_accurate', $user->info_accurate ?? false) ? 'checked' : '' }}> I confirm that the information provided is accurate and complete.</label>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_authorize" {{ old('info_authorize', $user->info_authorize ?? false) ? 'checked' : '' }}> I authorize the organization to use this information for official HR and statutory purposes.</label>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_falsified" {{ old('info_falsified', $user->info_falsified ?? false) ? 'checked' : '' }}> I understand that falsified information may lead to disciplinary action.</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Employee Info</button>
                        </form>
                    </div>
                    {{-- ===== END EMPLOYEE INFO TAB ===== --}}

                    {{-- ===== 6. TEAM INFO TAB ===== --}}
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
                    {{-- ===== END TEAM INFO TAB ===== --}}

                </div>
                {{-- ===== END TAB CONTENT ===== --}}

            </div>
        </div>
    </div>

    {{-- ===== PROFILE IMAGE SECTION ===== --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ $user->present()->avatar }}" alt="{{ $user->present()->name }}" class="img-fluid rounded-circle" width="150" id="profileImage">
                    <div id="changePhotoBtn"></div>
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
                data: { county_id: countyId },
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
                data: { subcounty_id: subcountyId },
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