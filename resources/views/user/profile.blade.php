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
    $activeTab = session('tab') ?? 'details';
    $useManualBranch = optional($user->manualBankDetails)->use_manual_details ?? false;
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
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
                    <!-- Details Tab -->
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
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">@lang('Last Name')</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">@lang('Phone')</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">@lang('Email')</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Location fields -->
                                    <div class="form-group">
                                        <label for="county">@lang('County')</label>
                                        <select id="county" name="county_id" class="form-control">
                                            @foreach($counties as $id => $name)
                                                <option value="{{ $id }}" {{ $user->county_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subcounty">@lang('Subcounty')</label>
                                        <select id="subcounty" name="subcounty_id" class="form-control">
                                            <!-- Options loaded dynamically -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ward">@lang('Ward')</label>
                                        <select id="ward" name="ward_id" class="form-control">
                                            <!-- Options loaded dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Details')</button>
                        </form>
                    </div>

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
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('Update Password')</button>
                        </form>
                    </div>

                    <!-- Two Factor Tab -->
                    <div class="tab-pane fade {{ $activeTab == '2fa' ? 'show active' : '' }}" 
                         id="two-factor" 
                         role="tabpanel" 
                         aria-labelledby="2fa-tab">
                        <form action="{{ route('two-factor.enable') }}" method="POST" id="two-factor-form">
                            @csrf
                            <button type="submit" class="btn btn-primary">@lang('Enable')</button>
                        </form>
                    </div>


                    <!-- Sensitive Info Tab -->
                    @if(auth()->user()->hasPermission(['sensitive.information.view', 'sensitive.information.manage'], false))
                    <div class="tab-pane fade {{ $activeTab == 'sensitive' ? 'show active' : '' }}" 
                         id="sensitive-info" 
                         role="tabpanel" 
                         aria-labelledby="sensitive-info-tab">
                        
                        @if(auth()->user()->hasPermission('sensitive.information.manage'))
                        <form action="{{ route('profile.update.sensitive-info') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <!-- Personal Documents Section -->
                            <h5 class="mb-4">@lang('Personal Documents')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_number">@lang('ID Number')</label>
                                        <input type="text" 
                                               class="form-control @error('id_number') is-invalid @enderror" 
                                               id="id_number" 
                                               name="id_number" 
                                               value="{{ old('id_number', $userDocument->id_number ?? '') }}">
                                        @error('id_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kra_pin">@lang('KRA PIN')</label>
                                        <input type="text" 
                                               class="form-control @error('kra_pin') is-invalid @enderror" 
                                               id="kra_pin" 
                                               name="kra_pin" 
                                               value="{{ old('kra_pin', $userDocument->kra_pin ?? '') }}">
                                        @error('kra_pin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Details Section -->
                            <h5 class="mb-4 mt-5">@lang('Bank Details')</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_id">@lang('Bank Name')</label>
                                        <select class="form-control @error('bank_id') is-invalid @enderror" 
                                                id="bank_id" 
                                                name="bank_id">
                                            <option value="">@lang('Select a bank')</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" 
                                                    {{ old('bank_id', optional(optional($bankDetails)->bank)->id) == $bank->id ? 'selected' : '' }}>
                                                    {{ $bank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="use_manual_details" 
                                                   name="use_manual_details"
                                                   {{ $useManualBranch ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="use_manual_details">
                                                @lang('Enter Branch Details Manually')
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Manual Branch Input -->
                                    <div id="manual_branch_fields" style="{{ $useManualBranch ? '' : 'display: none;' }}">
                                        <div class="form-group">
                                            <label for="manual_branch_name">@lang('Branch Name')</label>
                                            <input type="text" 
                                                   class="form-control @error('manual_branch_name') is-invalid @enderror" 
                                                   id="manual_branch_name" 
                                                   name="manual_branch_name" 
                                                   value="{{ old('manual_branch_name', optional($user->manualBankDetails)->manual_branch_name) }}">
                                            @error('manual_branch_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="manual_branch_code">@lang('Branch Code')</label>
                                            <input type="text" 
                                                   class="form-control @error('manual_branch_code') is-invalid @enderror" 
                                                   id="manual_branch_code" 
                                                   name="manual_branch_code" 
                                                   value="{{ old('manual_branch_code', optional($user->manualBankDetails)->manual_branch_code) }}">
                                            @error('manual_branch_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Branch Dropdown -->
                                    <div id="branch_dropdown" style="{{ $useManualBranch ? 'display: none;' : '' }}">
                                        <div class="form-group">
                                            <label for="bank_branch_code">@lang('Bank Branch')</label>
                                            <select class="form-control @error('bank_branch_code') is-invalid @enderror" 
                                                    id="bank_branch_code" 
                                                    name="bank_branch_code">
                                                <option value="">@lang('Select a bank first')</option>
                                            </select>
                                            @error('bank_branch_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name">@lang('Account Name')</label>
                                        <input type="text" 
                                               class="form-control @error('account_name') is-invalid @enderror" 
                                               id="account_name" 
                                               name="account_name" 
                                               value="{{ old('account_name', optional($bankDetails)->account_name) }}">
                                        @error('account_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number">@lang('Account Number')</label>
                                        <input type="text" 
                                               class="form-control @error('account_number') is-invalid @enderror" 
                                               id="account_number" 
                                               name="account_number" 
                                               value="{{ old('account_number', optional($bankDetails)->account_number) }}">
                                        @error('account_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('Update Information')
                                </button>
                            </div>
                        </form>
                        @else
                        <!-- Read-only view -->
                        <div class="row">
                        <div class="col-md-6">
                                <p><strong>@lang('ID Number'):</strong> {{ $userDocument->id_number ?? 'N/A' }}</p>
                                <p><strong>@lang('KRA PIN'):</strong> {{ $userDocument->kra_pin ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($bankDetails)
                        <h5 class="mb-4 mt-5">@lang('Bank Details')</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>@lang('Bank'):</strong> {{ optional($bankDetails->bank)->name ?? 'N/A' }}</p>
                                @if(optional($user->manualBankDetails)->use_manual_details)
                                    <p><strong>@lang('Branch'):</strong> {{ optional($user->manualBankDetails)->manual_branch_name ?? 'N/A' }}</p>
                                    <p><strong>@lang('Branch Code'):</strong> {{ optional($user->manualBankDetails)->manual_branch_code ?? 'N/A' }}</p>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Picture Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ $user->present()->avatar }}" 
                         alt="{{ $user->present()->name }}" 
                         class="img-fluid rounded-circle" 
                         width="150" 
                         id="profileImage">
                    <div id="changePhotoBtn"></div>
                </div>
                <h4 class="card-title mt-3">{{ $user->present()->name }}</h4>
                <p class="text-muted">{{ $user->present()->email }}</p>
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
  document.addEventListener('DOMContentLoaded', function() {
    // Profile Image Upload Handling
    initializeProfileImageHandling();

    // Bank Branch Form Handling
    initializeBankBranchForm();

    // Location Fields Handling
    initializeLocationFields();
});

function initializeProfileImageHandling() {
    const profileImage = document.getElementById('profileImage');
    const changePhotoBtn = document.getElementById('changePhotoBtn');
    const avatarInput = document.getElementById('avatar');
    const avatarForm = document.getElementById('avatarForm');

    if (profileImage && changePhotoBtn && avatarInput && avatarForm) {
        const openFileSelector = () => avatarInput.click();
        
        profileImage.addEventListener('click', openFileSelector);
        changePhotoBtn.addEventListener('click', openFileSelector);

        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                avatarForm.submit();
            }
        });
    }
}

function initializeBankBranchForm() {
    const elements = {
        bankSelect: document.getElementById('bank_id'),
        branchSelect: document.getElementById('bank_branch_code'),
        useManualDetails: document.getElementById('use_manual_details'),
        manualBranchFields: document.getElementById('manual_branch_fields'),
        branchDropdown: document.getElementById('branch_dropdown'),
        manualBranchName: document.getElementById('manual_branch_name'),
        manualBranchCode: document.getElementById('manual_branch_code')
    };

    // Exit if required elements are not found
    if (!elements.bankSelect || !elements.branchSelect || !elements.useManualDetails) {
        console.warn('Required bank form elements not found');
        return;
    }

    // Initialize form state
    const savedData = {
        bankId: elements.bankSelect.value,
        branchCode: elements.branchSelect.getAttribute('data-saved-branch') || '',
        useManual: elements.useManualDetails.checked
    };

    // Handle manual/dropdown toggle
    elements.useManualDetails.addEventListener('change', function() {
        handleManualToggle(this.checked, elements);
    });

    // Handle bank selection change
    elements.bankSelect.addEventListener('change', function() {
        if (this.value && !elements.useManualDetails.checked) {
            populateBranches(this.value, elements.branchSelect, savedData.branchCode);
        } else {
            resetBranchSelect(elements.branchSelect);
        }
    });

    // Load initial branches if bank is selected and not using manual mode
    if (savedData.bankId && !savedData.useManual) {
        populateBranches(savedData.bankId, elements.branchSelect, savedData.branchCode);
    }

    // Initial toggle state
    handleManualToggle(savedData.useManual, elements);
}

function handleManualToggle(isManual, elements) {
    // Toggle visibility
    elements.manualBranchFields.style.display = isManual ? 'block' : 'none';
    elements.branchDropdown.style.display = isManual ? 'none' : 'block';

    // Handle required fields and validation
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

    // Clear validation states
    clearValidationState([
        elements.branchSelect,
        elements.manualBranchName,
        elements.manualBranchCode
    ]);
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
            if (savedBranchCode && savedBranchCode === branch.code) {
                option.selected = true;
            }
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
    elements.forEach(element => {
        if (element) {
            element.classList.remove('is-invalid');
            const feedback = element.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.style.display = 'none';
            }
        }
    });
}

function initializeLocationFields() {
    const elements = {
        county: document.getElementById('county'),
        subcounty: document.getElementById('subcounty'),
        ward: document.getElementById('ward')
    };

    if (elements.county) {
        elements.county.addEventListener('change', async function() {
            const countyId = this.value;
            try {
                if (countyId) {
                    const response = await fetch(`/get-subcounties?county_id=${countyId}`);
                    const data = await response.json();
                    updateLocationSelect(elements.subcounty, data, 'Select a Subcounty');
                } else {
                    resetLocationSelects(elements);
                }
            } catch (error) {
                console.error('Error fetching subcounties:', error);
            }
        });
    }

    if (elements.subcounty) {
        elements.subcounty.addEventListener('change', async function() {
            const subcountyId = this.value;
            try {
                if (subcountyId) {
                    const response = await fetch(`/get-wards?subcounty_id=${subcountyId}`);
                    const data = await response.json();
                    updateLocationSelect(elements.ward, data, 'Select a Ward');
                } else {
                    elements.ward.innerHTML = '<option value="">Select a Subcounty first</option>';
                }
            } catch (error) {
                console.error('Error fetching wards:', error);
            }
        });
    }
}

function updateLocationSelect(select, data, defaultText) {
    if (!select) return;
    
    select.innerHTML = `<option value="">${defaultText}</option>`;
    Object.entries(data).forEach(([id, name]) => {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = name;
        select.appendChild(option);
    });
}

function resetLocationSelects(elements) {
    if (elements.subcounty) {
        elements.subcounty.innerHTML = '<option value="">Select a County first</option>';
    }
    if (elements.ward) {
        elements.ward.innerHTML = '<option value="">Select a Subcounty first</option>';
    }
}
    </script>
@endsection