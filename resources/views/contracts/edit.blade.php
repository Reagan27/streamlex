@extends('layouts.app')

@section('page-title', __('Edit Contract'))
@section('page-heading', __('Edit Contract'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Edit Contract')
    </li>
@stop

@section('styles')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        #signature-pad {
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            width: 100%;
            max-width: 400px;
        }
        .authority-signature-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .authority-fields {
            margin-bottom: 20px;
        }
        #signature-pad canvas {
            width: 100%;
            height: 200px;
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
        .slider {
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
        .slider:before {
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
        input:checked + .slider {
            background-color: #28a745;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .activation-warning {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border-left: 4px solid #dc3545;
            background-color: #fff3f3;
        }
    </style>
@endsection

@section('content')
@include('partials.messages')
<div class="card">
    <div class="card-body">
    <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data" id="contract-form">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $contract->title) }}" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ old('start_date', $contract->start_date ? $contract->start_date->format('Y-m-d') : '') }}" required>
                </div>
                <div class="form-group">
                    <label for="number_of_days">Number of Days</label>
                    <input type="number" class="form-control" id="number_of_days" name="number_of_days" value="{{ old('number_of_days', $contract->number_of_days) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select class="form-control" id="role_id" name="role_id" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $contract->role_id == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="counties">Counties</label>
                    <select class="form-control" id="counties" name="counties[]" multiple required>
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" {{ $contract->counties->contains($county->id) ? 'selected' : '' }}>
                                {{ $county->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="draft" {{ old('status', $contract->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $contract->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="dropped" {{ old('status', $contract->status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="d-block">Contract Activation Status</label>
            <div class="d-flex align-items-center mb-2">
                <label class="toggle-switch mr-3 mb-0">
                    <input type="checkbox" id="active_for_onboarding" name="active_for_onboarding" value="1"
                           {{ old('active_for_onboarding', $contract->active_for_onboarding) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <span class="status-label">{{ $contract->active_for_onboarding ? 'Active for Onboarding' : 'Inactive for Onboarding' }}</span>
            </div>
            
            <div class="activation-warning">
                <h6 class="font-weight-bold text-danger">Warning: Deactivating Contract</h6>
                <p class="mb-2">Deactivating this contract will:</p>
                <ul class="mb-0">
                    <li>Prevent new users from using this contract during onboarding</li>
                    <li>Mark existing contracts as inactive for all current users</li>
                    <li>Require manual reactivation to make it available again</li>
                </ul>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $contract->description) }}</textarea>
        </div>
        
        <div class="authority-signature-section">
                <h4>Authority Details</h4>
                <div class="authority-fields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="authority_name">Authority Name</label>
                                <input type="text" class="form-control" id="authority_name" 
                                       name="authority_name" 
                                       value="{{ old('authority_name', $contract->authority_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="authority_designation">Authority Designation</label>
                                <input type="text" class="form-control" id="authority_designation" 
                                       name="authority_designation" 
                                       value="{{ old('authority_designation', $contract->authority_designation) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="signature">Authority Signature</label>
                    <div id="signature-pad" class="signature-pad">
                        <canvas></canvas>
                    </div>
                    <input type="hidden" name="authority_signature" id="signature-data" 
                           value="{{ old('authority_signature', $contract->authority_signature) }}">
                    <div class="mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="clear-signature">Clear Signature</button>
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <label for="change_reason">Reason for Change</label>
                <textarea class="form-control" id="change_reason" name="change_reason" required 
                          placeholder="Please provide a reason for updating this contract"></textarea>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary" id="submit-contract">Update Contract</button>
                </div>
            </div>
        
       
    </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#counties').select2({
                placeholder: 'Select counties',
                allowClear: true
            });

            // Initialize Summernote
            $('#description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            
            // Initialize SignaturePad
            var canvas = document.querySelector("#signature-pad canvas");
            var signaturePad = new SignaturePad(canvas, {
                minWidth: 1,
                maxWidth: 2,
                penColor: "black"
            });

            // Handle activation toggle
            const activationToggle = document.getElementById('active_for_onboarding');
            const activationWarning = document.querySelector('.activation-warning');
            const statusLabel = document.querySelector('.status-label');
            const contractForm = document.getElementById('contract-form');

            activationToggle.addEventListener('change', function() {
                const isActive = this.checked;
                statusLabel.textContent = isActive ? 'Active for Onboarding' : 'Inactive for Onboarding';
                activationWarning.style.display = isActive ? 'none' : 'block';
            });

            // Initial warning display
            activationWarning.style.display = activationToggle.checked ? 'none' : 'block';

            // Form submission handling
            contractForm.addEventListener('submit', function(e) {
                // If deactivating the contract
                if (!activationToggle.checked && {{ $contract->active_for_onboarding ? 'true' : 'false' }}) {
                    if (!confirm('Are you sure you want to deactivate this contract? This will affect all users currently using this contract.')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Signature validation
                if (signaturePad.isEmpty() && !document.getElementById('signature-data').value) {
                    e.preventDefault();
                    alert('Please provide an authority signature');
                    return false;
                }

                // Update signature data if changed
                if (!signaturePad.isEmpty()) {
                    document.getElementById('signature-data').value = signaturePad.toDataURL();
                }
            });

            // Signature pad functions
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            // Load existing signature
            var existingSignature = document.getElementById('signature-data').value;
            if (existingSignature) {
                signaturePad.fromDataURL(existingSignature);
            }

            // Clear signature
            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
                document.getElementById('signature-data').value = '';
            });
        });
    </script>
@endsection