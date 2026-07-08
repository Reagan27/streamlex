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

            <!-- Contract Type Selection -->
            <div class="form-group">
                <label>Contract Type</label>
                <div>
                    <label class="mr-3">
                        <input type="radio" name="contract_category" value="group" {{ $contract->contract_category === 'group' ? 'checked' : '' }}> Group
                    </label>
                    <label>
                        <input type="radio" name="contract_category" value="individual" {{ $contract->contract_category === 'individual' ? 'checked' : '' }}> Individual
                    </label>
                </div>
            </div>

            <!-- Group-specific fields -->
            <div class="group-only" style="display: none;">
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select class="form-control" id="role_id" name="role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $contract->role_id == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="counties">Counties</label>
                    <select class="form-control" id="counties" name="counties[]" multiple="multiple">
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" {{ in_array($county->id, $contract->counties->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $county->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Individual-specific fields -->
            <div class="individual-only" style="display: none;">
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select class="form-control" id="user_id" name="user_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $contract->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="engagement_type">Engagement Type</label>
                    <select class="form-control" id="engagement_type" name="engagement_type">
                        <option value="consultant" {{ $contract->engagement_type === 'consultant' ? 'selected' : '' }}>Consultant</option>
                        <option value="employee" {{ $contract->engagement_type === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="parttime" {{ $contract->engagement_type === 'parttime' ? 'selected' : '' }}>Part-time</option>
                    </select>
                </div>
            </div>

            <!-- Common fields for both types -->
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="duration_type">Duration Type</label>
                    <select class="form-control" id="duration_type" name="duration_type">
                        <option value="">Select duration</option>
                        @foreach($durationTypes as $type)
                            <option value="{{ $type }}" {{ $contract->duration_type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="duration_amount">Duration Time</label>
                    <input type="number" class="form-control" id="duration_amount" name="duration_amount" min="1"
                           value="{{ old('duration_amount', $contract->number_of_days) }}">
                    <small class="form-text text-muted">Specify the duration value based on the selected type (years, months, or days).</small>
                </div>
            </div>

            <div class="form-group">
                <label for="project_id">Project</label>
                <select class="form-control" id="project_id" name="project_id">
                    <option value="">Select a Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $contract->project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
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
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                       value="{{ old('start_date', $contract->start_date ? $contract->start_date->format('Y-m-d') : '') }}" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                       value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
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
                           value="{{ old('authority_signature', $signature->signature ?? $contract->authority_signature) }}">
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

            // Contract type toggle
            const contractTypeRadios = document.querySelectorAll('input[name="contract_category"]');
            const toggleFields = () => {
                const selectedType = document.querySelector('input[name="contract_category"]:checked').value;
                document.querySelectorAll('.group-only').forEach(el => el.style.display = selectedType === 'group' ? 'block' : 'none');
                document.querySelectorAll('.individual-only').forEach(el => el.style.display = selectedType === 'individual' ? 'block' : 'none');
            };
            toggleFields(); // run on page load to show correct fields
            contractTypeRadios.forEach(radio => radio.addEventListener('change', toggleFields));

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
                if (!activationToggle.checked && {{ $contract->active_for_onboarding ? 'true' : 'false' }}) {
                    if (!confirm('Are you sure you want to deactivate this contract? This will affect all users currently using this contract.')) {
                        e.preventDefault();
                        return false;
                    }
                }

                if (signaturePad.isEmpty() && !document.getElementById('signature-data').value) {
                    e.preventDefault();
                    alert('Please provide an authority signature');
                    return false;
                }

                if (!signaturePad.isEmpty()) {
                    document.getElementById('signature-data').value = signaturePad.toDataURL();
                }
            });

            // Signature pad resize
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