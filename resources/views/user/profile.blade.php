@extends('layouts.app')

@section('styles')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('page-title', __('My Profile'))
@section('page-heading', __('My Profile'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('My Profile')
    </li>
@stop

@section('content')
@include('partials.messages')

@php
    $activeTab = request('tab') ?? session('tab') ?? 'details';
    $useManualBranch = optional($profileUser->manualBankDetails)->use_manual_details ?? false;
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'employeeinfo' ? 'active' : '' }}"
                           id="employeeinfo-tab"
                           data-toggle="tab"
                           href="#employeeinfo"
                           role="tab"
                           aria-controls="employeeinfo"
                           aria-selected="{{ $activeTab == 'employeeinfo' ? 'true' : 'false' }}">
                            Employee Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'details' ? 'active' : '' }}"
                           id="details-tab"
                           data-toggle="tab"
                           href="#details"
                           role="tab"
                           aria-controls="details"
                           aria-selected="{{ $activeTab == 'details' ? 'true' : 'false' }}">
                            @lang('User Details')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}"
                           id="password-tab"
                           data-toggle="tab"
                           href="#password"
                           role="tab"
                           aria-controls="password"
                           aria-selected="{{ $activeTab == 'password' ? 'true' : 'false' }}">
                            @lang('Update Password')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == '2fa' ? 'active' : '' }}"
                           id="2fa-tab"
                           data-toggle="tab"
                           href="#two-factor"
                           role="tab"
                           aria-controls="two-factor"
                           aria-selected="{{ $activeTab == '2fa' ? 'true' : 'false' }}">
                            @lang('Two-Factor Authentication')
                        </a>
                    </li>

                    @if(auth()->user()->hasPermission(['sensitive.information.view', 'sensitive.information.manage'], false))
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'sensitive' ? 'active' : '' }}"
                           id="sensitive-info-tab"
                           data-toggle="tab"
                           href="#sensitive-info"
                           role="tab"
                           aria-controls="sensitive-info"
                           aria-selected="{{ $activeTab == 'sensitive' ? 'true' : 'false' }}">
                            @lang('Sensitive Information')
                        </a>
                    </li>
                    @endif
                </ul>

                <div class="tab-content mt-4" id="nav-tabContent">

                    <!-- ==================== Employee Info Tab ==================== -->
                    <div class="tab-pane fade {{ $activeTab == 'employeeinfo' ? 'show active' : '' }}"
                         id="employeeinfo"
                         role="tabpanel"
                         aria-labelledby="employeeinfo-tab">

                        <form action="{{ route('profile.update.employeeinfo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $profileUser->id }}">

                            <h5 class="mb-3">Next of Kin / Emergency Contact</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" name="nok_full_name"
                                               value="{{ old('nok_full_name', $profileUser->nok_full_name ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Relationship</label>
                                        <input type="text" class="form-control" name="nok_relationship"
                                               value="{{ old('nok_relationship', $profileUser->nok_relationship ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="nok_mobile"
                                               value="{{ old('nok_mobile', $profileUser->nok_mobile ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Alternative Phone Number</label>
                                        <input type="text" class="form-control" name="nok_alt_phone"
                                               value="{{ old('nok_alt_phone', $profileUser->nok_alt_phone ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="nok_email"
                                               value="{{ old('nok_email', $profileUser->nok_email ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Physical Address</label>
                                        <input type="text" class="form-control" name="nok_address"
                                               value="{{ old('nok_address', $profileUser->nok_address ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Marital & Family Information</h5>
                            <div class="form-group">
                                <label>Marital Status</label><br>
                                @foreach(['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'separated' => 'Separated', 'widowed' => 'Widowed'] as $val => $label)
                                    <label class="mr-3">
                                        <input type="radio" name="marital_status" value="{{ $val }}"
                                               {{ old('marital_status', $profileUser->marital_status ?? '') == $val ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label>Spouse Name (if applicable)</label>
                                <input type="text" class="form-control" name="spouse_name"
                                       value="{{ old('spouse_name', $profileUser->spouse_name ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Spouse Contact Number</label>
                                <input type="text" class="form-control" name="spouse_contact"
                                       value="{{ old('spouse_contact', $profileUser->spouse_contact ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Number of Dependents</label>
                                <input type="number" class="form-control" name="dependents"
                                       value="{{ old('dependents', $profileUser->dependents ?? '') }}">
                            </div>

                            <hr>
                            <h5 class="mb-3">Employment Details</h5>
                            @php
                                $isManagerOrAdmin = auth()->user()->hasRole(['Admin', 'Manager']);
                                $empDetailsSet = $profileUser->employee_number || $profileUser->department || $profileUser->job_title;
                            @endphp
                            @if($isManagerOrAdmin || $empDetailsSet)
                                <div class="form-group">
                                    <label>Employee Number</label>
                                    <input type="text" class="form-control" name="employee_number"
                                           value="{{ old('employee_number', $profileUser->employee_number ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" class="form-control" name="department"
                                           value="{{ old('department', $profileUser->department ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input type="text" class="form-control" name="job_title"
                                           value="{{ old('job_title', $profileUser->job_title ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                            @endif

                            @php
                                $employmentFieldsSet = $profileUser->employment_type || $profileUser->employment_date
                                    || $profileUser->work_station || $profileUser->supervisor_name || $profileUser->supervisor_title;
                            @endphp
                            @if($isManagerOrAdmin || $employmentFieldsSet)
                                <div class="form-group">
                                    <label>Employment Type</label><br>
                                    @foreach(['permanent' => 'Permanent', 'contract' => 'Contract', 'parttime' => 'Part-Time', 'casual' => 'Casual', 'intern' => 'Intern'] as $val => $label)
                                        <label class="mr-3">
                                            <input type="radio" name="employment_type" value="{{ $val }}"
                                                   {{ old('employment_type', $profileUser->employment_type ?? '') == $val ? 'checked' : '' }}
                                                   @if(!$isManagerOrAdmin) disabled @endif>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>Date of Employment</label>
                                    <input type="date" class="form-control" name="employment_date"
                                           value="{{ old('employment_date', $profileUser->employment_date ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label>Work Station / Duty Station</label>
                                    <input type="text" class="form-control" name="work_station"
                                           value="{{ old('work_station', $profileUser->work_station ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label>Supervisor's Name</label>
                                    <input type="text" class="form-control" name="supervisor_name"
                                           value="{{ old('supervisor_name', $profileUser->supervisor_name ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label>Supervisor's Title</label>
                                    <input type="text" class="form-control" name="supervisor_title"
                                           value="{{ old('supervisor_title', $profileUser->supervisor_title ?? '') }}"
                                           @if(!$isManagerOrAdmin) readonly @endif>
                                </div>
                            @endif

                            <hr>
                            <h5 class="mb-3">Education & Professional Qualifications</h5>
                            <div class="form-group">
                                <label>Upload Certificates (multiple, any type)</label>
                                <div id="cert-upload-list">
                                    <div class="row mb-2 cert-upload-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="certificate_names[]"
                                                   placeholder="Certificate Name (e.g., KRA, KCSE)">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="certificates[]">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="this.closest('.cert-upload-row').remove()">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addCertRow()">Add Another</button>
                                <small class="form-text text-muted">Allowed: PDF, JPG, PNG, DOC, DOCX, etc.</small>
                            </div>

                            @if(isset($certificates) && count($certificates))
                                <ul class="list-group mb-3">
                                    @foreach($certificates as $cert)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $cert->level ?? 'Document' }}</span>
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($cert->file_path) }}"
                                               target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="mt-4 mb-2">
                                <h6>Other Documents</h6>
                                <div id="other-doc-upload-list">
                                    <div class="row mb-2 other-doc-upload-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="other_doc_names[]"
                                                   placeholder="Document Name (e.g., Good Conduct, NHIF)">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="other_docs[]">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="this.closest('.other-doc-upload-row').remove()">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addOtherDocRow()">Add Another</button>
                                <small class="form-text text-muted">Upload any other relevant documents here.</small>
                            </div>

                            <hr>
                            <h5 class="mb-3">Medical Information</h5>
                            <div class="form-group">
                                <label>Blood Group (optional)</label>
                                <input type="text" class="form-control" name="blood_group"
                                       value="{{ old('blood_group', $profileUser->blood_group ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Known Medical Conditions (optional)</label>
                                <input type="text" class="form-control" name="medical_conditions"
                                       value="{{ old('medical_conditions', $profileUser->medical_conditions ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Allergies (if any)</label>
                                <input type="text" class="form-control" name="allergies"
                                       value="{{ old('allergies', $profileUser->allergies ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Preferred Medical Facility</label>
                                <input type="text" class="form-control" name="medical_facility"
                                       value="{{ old('medical_facility', $profileUser->medical_facility ?? '') }}">
                            </div>

                            <hr>
                            <h5 class="mb-3">Disability Information (Optional)</h5>
                            <div class="form-group">
                                <label>Do you have any disability?</label><br>
                                <label class="mr-3">
                                    <input type="radio" name="disability" value="yes" id="disability_yes"
                                           {{ old('disability', $profileUser->disability ?? '') == 'yes' ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <label>
                                    <input type="radio" name="disability" value="no" id="disability_no"
                                           {{ old('disability', $profileUser->disability ?? '') == 'no' ? 'checked' : '' }}>
                                    No
                                </label>
                            </div>
                            <div class="form-group" id="disability_details_group" style="display: none;">
                                <label>If yes, specify</label>
                                <input type="text" class="form-control" name="disability_details"
                                       value="{{ old('disability_details', $profileUser->disability_details ?? '') }}">
                            </div>

                            <hr>
                            <h5 class="mb-3">Statutory & Compliance Declarations</h5>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_accurate" {{ old('info_accurate', $profileUser->info_accurate ?? false) ? 'checked' : '' }}> I confirm that the information provided is accurate and complete.</label>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_authorize" {{ old('info_authorize', $profileUser->info_authorize ?? false) ? 'checked' : '' }}> I authorize the organization to use this information for official HR and statutory purposes.</label>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" name="info_falsified" {{ old('info_falsified', $profileUser->info_falsified ?? false) ? 'checked' : '' }}> I understand that falsified information may lead to disciplinary action.</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Employee Info</button>
                        </form>
                    </div>

                    <!-- ==================== Details Tab ==================== -->
                    <div class="tab-pane fade {{ $activeTab == 'details' ? 'show active' : '' }}"
                         id="details"
                         role="tabpanel"
                         aria-labelledby="details-tab">
                        <form action="{{ route('profile.update.details') }}" method="POST" id="details-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">@lang('First Name')</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                               value="{{ old('first_name', $profileUser->first_name) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">@lang('Last Name')</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                               value="{{ old('last_name', $profileUser->last_name) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">@lang('Phone')</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               value="{{ old('phone', $profileUser->phone) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">@lang('Email')</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{ old('email', $profileUser->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="county">@lang('County')</label>
                                        <select id="county" name="county_id" class="form-control">
                                            @foreach($counties as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $profileUser->county_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subcounty">@lang('Subcounty')</label>
                                        <select id="subcounty" name="subcounty_id" class="form-control">
                                            @if(isset($subcounties))
                                                <option value="">Select a Subcounty</option>
                                                @foreach($subcounties as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $profileUser->subcounty_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Select a County first</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ward">@lang('Ward')</label>
                                        <select id="ward" name="ward_id" class="form-control">
                                            @if(isset($wards))
                                                <option value="">Select a Ward</option>
                                                @foreach($wards as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $profileUser->ward_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Select a Subcounty first</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Details')</button>
                        </form>
                    </div>

                    <!-- ==================== Password Tab ==================== -->
                    <div class="tab-pane fade {{ $activeTab == 'password' ? 'show active' : '' }}"
                         id="password"
                         role="tabpanel"
                         aria-labelledby="password-tab">
                        <form action="{{ route('profile.update.login-details') }}" method="POST" id="password-form">
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
                                        <input type="password" class="form-control" id="password_confirmation"
                                               name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Password')</button>
                        </form>
                    </div>

                    <!-- ==================== Two Factor Tab ==================== -->
                    <div class="tab-pane fade {{ $activeTab == '2fa' ? 'show active' : '' }}"
                         id="two-factor"
                         role="tabpanel"
                         aria-labelledby="2fa-tab">
                        <form action="{{ route('two-factor.enable') }}" method="POST" id="two-factor-form">
                            @csrf
                            <button type="submit" class="btn btn-primary">@lang('Enable')</button>
                        </form>
                    </div>

                    <!-- ==================== Sensitive Info Tab ==================== -->
                    @if(auth()->user()->hasPermission(['sensitive.information.view', 'sensitive.information.manage'], false))
                    <div class="tab-pane fade {{ $activeTab == 'sensitive' ? 'show active' : '' }}"
                         id="sensitive-info"
                         role="tabpanel"
                         aria-labelledby="sensitive-info-tab">

                        @if(auth()->user()->hasPermission('sensitive.information.manage'))
                        <form action="{{ route('profile.update.sensitive-info') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h5 class="mb-4">@lang('Personal Documents')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_number">@lang('ID Number')</label>
                                        <input type="text"
                                               class="form-control @error('id_number') is-invalid @enderror"
                                               id="id_number" name="id_number"
                                               value="{{ old('id_number', $userDocument->id_number ?? '') }}">
                                        @error('id_number')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kra_pin">@lang('KRA PIN')</label>
                                        <input type="text"
                                               class="form-control @error('kra_pin') is-invalid @enderror"
                                               id="kra_pin" name="kra_pin"
                                               value="{{ old('kra_pin', $userDocument->kra_pin ?? '') }}">
                                        @error('kra_pin')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-4 mt-5">@lang('Bank Details')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_id">@lang('Bank Name')</label>
                                        <select class="form-control @error('bank_id') is-invalid @enderror"
                                                id="bank_id" name="bank_id">
                                            <option value="">@lang('Select a bank')</option>
                                            @if(isset($banks) && $banks)
                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->id }}"
                                                        {{ old('bank_id', optional(optional($bankDetails)->bank)->id) == $bank->id ? 'selected' : '' }}>
                                                        {{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('bank_id')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="use_manual_details" name="use_manual_details"
                                                   {{ $useManualBranch ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="use_manual_details">
                                                @lang('Enter Branch Details Manually')
                                            </label>
                                        </div>
                                    </div>

                                    <div id="manual_branch_fields" style="{{ $useManualBranch ? '' : 'display: none;' }}">
                                        <div class="form-group">
                                            <label for="manual_branch_name">@lang('Branch Name')</label>
                                            <input type="text"
                                                   class="form-control @error('manual_branch_name') is-invalid @enderror"
                                                   id="manual_branch_name" name="manual_branch_name"
                                                   value="{{ old('manual_branch_name', optional($profileUser->manualBankDetails)->manual_branch_name) }}">
                                            @error('manual_branch_name')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="manual_branch_code">@lang('Branch Code')</label>
                                            <input type="text"
                                                   class="form-control @error('manual_branch_code') is-invalid @enderror"
                                                   id="manual_branch_code" name="manual_branch_code"
                                                   value="{{ old('manual_branch_code', optional($profileUser->manualBankDetails)->manual_branch_code) }}">
                                            @error('manual_branch_code')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="branch_dropdown" style="{{ $useManualBranch ? 'display: none;' : '' }}">
                                        <div class="form-group">
                                            <label for="bank_branch_code">@lang('Bank Branch')</label>
                                            <select class="form-control @error('bank_branch_code') is-invalid @enderror"
                                                    id="bank_branch_code" name="bank_branch_code">
                                                <option value="">@lang('Select a bank first')</option>
                                            </select>
                                            @error('bank_branch_code')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name">@lang('Account Name')</label>
                                        <input type="text"
                                               class="form-control @error('account_name') is-invalid @enderror"
                                               id="account_name" name="account_name"
                                               value="{{ old('account_name', optional($bankDetails)->account_name) }}">
                                        @error('account_name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number">@lang('Account Number')</label>
                                        <input type="text"
                                               class="form-control @error('account_number') is-invalid @enderror"
                                               id="account_number" name="account_number"
                                               value="{{ old('account_number', optional($bankDetails)->account_number) }}">
                                        @error('account_number')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">@lang('Update Information')</button>
                            </div>
                        </form>

                        @else
                        {{-- Read-only view --}}
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>@lang('ID Number'):</strong> {{ $userDocument->id_number ?? 'N/A' }}</p>
                                <p><strong>@lang('KRA PIN'):</strong> {{ $userDocument->kra_pin ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if(isset($bankDetails) && $bankDetails)
                        <h5 class="mb-4 mt-5">@lang('Bank Details')</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>@lang('Bank'):</strong> {{ optional($bankDetails->bank)->name ?? 'N/A' }}</p>
                                @if(optional($profileUser->manualBankDetails)->use_manual_details)
                                    <p><strong>@lang('Branch'):</strong> {{ optional($profileUser->manualBankDetails)->manual_branch_name ?? 'N/A' }}</p>
                                    <p><strong>@lang('Branch Code'):</strong> {{ optional($profileUser->manualBankDetails)->manual_branch_code ?? 'N/A' }}</p>
                                @else
                                    <p><strong>@lang('Branch'):</strong> {{ optional($bankDetails->bankBranch)->branch_name ?? 'N/A' }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>@lang('Account Name'):</strong> {{ $bankDetails->account_name ?? 'N/A' }}</p>
                                <p><strong>@lang('Account Number'):</strong> {{ $bankDetails->account_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        @endif

                    </div>
                    @endif

                </div>{{-- end tab-content --}}
            </div>
        </div>
    </div>

    <!-- ==================== Profile Picture Card ==================== -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ $profileUser->present()->avatar }}"
                         alt="{{ $profileUser->present()->name }}"
                         class="img-fluid rounded-circle"
                         width="150"
                         id="profileImage">
                    <div id="changePhotoBtn"></div>
                </div>
                <h4 class="card-title mt-3">{{ $profileUser->present()->name }}</h4>
                <p class="text-muted">{{ $profileUser->present()->email }}</p>
                <form action="{{ route('profile.update.avatar') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="avatarForm">
                    @csrf
                    <input type="file"
                           name="avatar"
                           id="avatar"
                           class="d-none"
                           accept="image/*"
                           required>
                </form>
            </div>
        </div>
    </div>

</div>
@stop

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initializeProfileImageHandling();
            initializeBankBranchForm();
            initializeLocationFields();
            initializeDisabilityToggle();
        });

        // ---- Profile image upload ----
        function initializeProfileImageHandling() {
            const profileImage   = document.getElementById('profileImage');
            const changePhotoBtn = document.getElementById('changePhotoBtn');
            const avatarInput    = document.getElementById('avatar');
            const avatarForm     = document.getElementById('avatarForm');

            if (profileImage && changePhotoBtn && avatarInput && avatarForm) {
                const openFileSelector = () => avatarInput.click();
                profileImage.addEventListener('click', openFileSelector);
                changePhotoBtn.addEventListener('click', openFileSelector);
                avatarInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) avatarForm.submit();
                });
            }
        }

        // ---- Certificate rows ----
        function addCertRow() {
            const row = document.createElement('div');
            row.className = 'row mb-2 cert-upload-row';
            row.innerHTML = `
                <div class='col-md-5'><input type='text' class='form-control' name='certificate_names[]' placeholder='Certificate Name (e.g., KRA, KCSE)'></div>
                <div class='col-md-5'><input type='file' class='form-control' name='certificates[]'></div>
                <div class='col-md-2'><button type='button' class='btn btn-danger btn-sm' onclick='this.closest(".cert-upload-row").remove()'>Remove</button></div>
            `;
            document.getElementById('cert-upload-list').appendChild(row);
        }

        function addOtherDocRow() {
            const row = document.createElement('div');
            row.className = 'row mb-2 other-doc-upload-row';
            row.innerHTML = `
                <div class='col-md-5'><input type='text' class='form-control' name='other_doc_names[]' placeholder='Document Name (e.g., Good Conduct, NHIF)'></div>
                <div class='col-md-5'><input type='file' class='form-control' name='other_docs[]'></div>
                <div class='col-md-2'><button type='button' class='btn btn-danger btn-sm' onclick='this.closest(".other-doc-upload-row").remove()'>Remove</button></div>
            `;
            document.getElementById('other-doc-upload-list').appendChild(row);
        }

        // ---- Disability toggle ----
        function initializeDisabilityToggle() {
            const yes   = document.getElementById('disability_yes');
            const no    = document.getElementById('disability_no');
            const group = document.getElementById('disability_details_group');

            function toggle() {
                if (yes && group) group.style.display = yes.checked ? '' : 'none';
            }

            if (yes) yes.addEventListener('change', toggle);
            if (no)  no.addEventListener('change', toggle);
            toggle();
        }

        // ---- Bank branch form ----
        function initializeBankBranchForm() {
            const elements = {
                bankSelect:        document.getElementById('bank_id'),
                branchSelect:      document.getElementById('bank_branch_code'),
                useManualDetails:  document.getElementById('use_manual_details'),
                manualBranchFields:document.getElementById('manual_branch_fields'),
                branchDropdown:    document.getElementById('branch_dropdown'),
                manualBranchName:  document.getElementById('manual_branch_name'),
                manualBranchCode:  document.getElementById('manual_branch_code')
            };

            if (!elements.bankSelect || !elements.branchSelect || !elements.useManualDetails) return;

            const savedData = {
                bankId:    elements.bankSelect.value,
                branchCode:elements.branchSelect.getAttribute('data-saved-branch') || '',
                useManual: elements.useManualDetails.checked
            };

            elements.useManualDetails.addEventListener('change', function () {
                handleManualToggle(this.checked, elements);
            });

            elements.bankSelect.addEventListener('change', function () {
                if (this.value && !elements.useManualDetails.checked) {
                    populateBranches(this.value, elements.branchSelect, savedData.branchCode);
                } else {
                    resetBranchSelect(elements.branchSelect);
                }
            });

            if (savedData.bankId && !savedData.useManual) {
                populateBranches(savedData.bankId, elements.branchSelect, savedData.branchCode);
            }

            handleManualToggle(savedData.useManual, elements);
        }

        function handleManualToggle(isManual, elements) {
            elements.manualBranchFields.style.display = isManual ? 'block' : 'none';
            elements.branchDropdown.style.display     = isManual ? 'none'  : 'block';

            if (isManual) {
                elements.branchSelect.removeAttribute('required');
                elements.manualBranchName.setAttribute('required', 'required');
                elements.manualBranchCode.setAttribute('required', 'required');
                elements.branchSelect.value = '';
            } else {
                elements.branchSelect.setAttribute('required', 'required');
                elements.manualBranchName.removeAttribute('required');
                elements.manualBranchCode.removeAttribute('required');
                elements.manualBranchName.value = '';
                elements.manualBranchCode.value = '';
            }

            clearValidationState([elements.branchSelect, elements.manualBranchName, elements.manualBranchCode]);
        }

        async function populateBranches(bankId, branchSelect, savedBranchCode = '') {
            try {
                const response = await fetch(`/profile/bank-branches?bank_id=${bankId}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const branches = await response.json();

                branchSelect.innerHTML = '<option value="">Select a branch</option>';
                branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.code;
                    option.textContent = branch.name;
                    if (savedBranchCode && savedBranchCode === branch.code) option.selected = true;
                    branchSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching branches:', error);
                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
            }
        }

        function resetBranchSelect(branchSelect) {
            branchSelect.innerHTML = '<option value="">Select a bank first</option>';
        }

        function clearValidationState(elements) {
            elements.forEach(el => {
                if (!el) return;
                el.classList.remove('is-invalid');
                const fb = el.nextElementSibling;
                if (fb && fb.classList.contains('invalid-feedback')) fb.style.display = 'none';
            });
        }

        // ---- Location dropdowns ----
        function initializeLocationFields() {
            const county    = document.getElementById('county');
            const subcounty = document.getElementById('subcounty');
            const ward      = document.getElementById('ward');

            if (county) {
                county.addEventListener('change', async function () {
                    if (this.value) {
                        try {
                            const res  = await fetch(`/get-subcounties?county_id=${this.value}`);
                            const data = await res.json();
                            updateLocationSelect(subcounty, data, 'Select a Subcounty');
                            updateLocationSelect(ward, {}, 'Select a Subcounty first');
                        } catch (e) { console.error(e); }
                    } else {
                        updateLocationSelect(subcounty, {}, 'Select a County first');
                        updateLocationSelect(ward, {}, 'Select a Subcounty first');
                    }
                });
            }

            if (subcounty) {
                subcounty.addEventListener('change', async function () {
                    if (this.value) {
                        try {
                            const res  = await fetch(`/get-wards?subcounty_id=${this.value}`);
                            const data = await res.json();
                            updateLocationSelect(ward, data, 'Select a Ward');
                        } catch (e) { console.error(e); }
                    } else {
                        updateLocationSelect(ward, {}, 'Select a Subcounty first');
                    }
                });
            }
        }

        function updateLocationSelect(select, data, defaultText) {
            if (!select) return;
            select.innerHTML = `<option value="">${defaultText}</option>`;
            Object.entries(data).forEach(([id, name]) => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                select.appendChild(opt);
            });
        }
    </script>
@endsection