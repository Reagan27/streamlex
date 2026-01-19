@extends('layouts.app')

@section('page-title', __('Import Payments'))
@section('page-heading', __('Import Payments'))

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: #495057;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }

    .import-card {
        height: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 0.5rem;
    }

    .template-download {
        margin-bottom: 20px;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
    }

    .alert {
        border-radius: 0.5rem;
    }

    .btn-outline-primary:hover {
        color: #fff;
    }

    .invalid-feedback {
        display: block;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #179970;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .toggle-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('payments.process-import') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="importForm">
                @csrf

                <div class="row">
                    <!-- Left Column - File Upload -->
                    <div class="col-md-6 mb-4">
                        <div class="card import-card">
                            <div class="card-body">
                                <div class="template-download mb-4">
                                    <h5 class="text-dark mb-3">Excel Template</h5>
                                    <p class="text-muted mb-3">Download the template for uploading payment information. The template includes all required fields and formatting guidelines.</p>
                                    <a href="{{ route('payments.download-template') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-file-download me-2"></i> Download Template
                                    </a>
                                </div>

                                <div class="form-group">
                                    <label for="file" class="form-label">Upload Excel File</label>
                                    <input type="file" 
                                           name="file" 
                                           id="file" 
                                           class="form-control @error('file') is-invalid @enderror" 
                                           accept=".xlsx,.xls,.csv"
                                           required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Cycle Selection -->
                    <div class="col-md-6 mb-4">
                        <div class="card import-card">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group mb-4">
                                    <label for="cycle_option" class="form-label">Payment Cycle Option</label>
                                    <select name="cycle_option" 
                                            id="cycle_option" 
                                            class="form-control @error('cycle_option') is-invalid @enderror" 
                                            required>
                                        <option value="existing">Use Existing Cycle</option>
                                        <option value="new">Create New Cycle</option>
                                    </select>
                                    @error('cycle_option')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="existing_cycle_fields">
                                    <div class="form-group mb-4">
                                        <label for="payment_cycle" class="form-label">Select Existing Cycle</label>
                                        <select name="payment_cycle" 
                                                id="payment_cycle" 
                                                class="form-control select2 @error('payment_cycle') is-invalid @enderror"
                                                style="width: 100%;">
                                            <option value="">Select a payment cycle</option>
                                            @foreach($paymentCycles as $cycle)
                                                <option value="{{ $cycle->id }}">{{ $cycle->description }}</option>
                                            @endforeach
                                        </select>
                                        @error('payment_cycle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="new_cycle_fields" style="display: none;">
                                    <div class="form-group mb-4">
                                        <label for="cycle_description" class="form-label">New Cycle Description</label>
                                        <input type="text" 
                                               name="cycle_description" 
                                               id="cycle_description" 
                                               class="form-control @error('cycle_description') is-invalid @enderror"
                                               placeholder="Enter cycle description">
                                        @error('cycle_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                        <label class="toggle-label d-block">Require Invoices</label>
                                        <div class="d-flex align-items-center">
                                            <label class="toggle-switch me-2">
                                                <input type="checkbox" 
                                                       name="is_invoicable" 
                                                       id="is_invoicable" 
                                                       value="1" 
                                                       checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <small class="text-muted mx-2"> Enable if payments in this cycle require invoice uploads</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                        <i class="fas fa-upload me-2"></i> Import Payments
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeSelect2();
    initializeFormValidation();
    initializeCycleToggle();
});

function initializeSelect2() {
    $('#payment_cycle').select2({
        placeholder: 'Select a payment cycle',
        allowClear: true,
        width: '100%',
        theme: 'classic'
    });
}

function initializeFormValidation() {
    const form = document.getElementById('importForm');
    const submitBtn = document.getElementById('submitBtn');
    const fileInput = document.getElementById('file');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to import');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Import Payments';
        }
    });
}

function initializeCycleToggle() {
    const cycleOption = document.getElementById('cycle_option');
    const existingFields = document.getElementById('existing_cycle_fields');
    const newFields = document.getElementById('new_cycle_fields');
    const paymentCycleSelect = document.getElementById('payment_cycle');
    const cycleDescription = document.getElementById('cycle_description');
    
    cycleOption.addEventListener('change', function() {
        if (this.value === 'existing') {
            existingFields.style.display = 'block';
            newFields.style.display = 'none';
            paymentCycleSelect.required = true;
            cycleDescription.required = false;
            $('#payment_cycle').select2('destroy');
            initializeSelect2();
        } else {
            existingFields.style.display = 'none';
            newFields.style.display = 'block';
            paymentCycleSelect.required = false;
            cycleDescription.required = true;
        }
    });
}
</script>
@endpush