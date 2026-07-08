@extends('layouts.app')

@section('page-title', __('Create Contract'))
@section('page-heading', __('Create Contract'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Create Contract')
    </li>
@stop

@section('styles')
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
    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
    <form id="contract-form" action="{{ route('contracts.store') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label>Contract Type</label>
            <div>
                <label class="mr-3"><input type="radio" name="contract_category" value="group" checked> Group</label>
                <label><input type="radio" name="contract_category" value="individual"> Individual</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="active_for_onboarding">Active for Onboarding</label>
                    <div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="active_for_onboarding" name="active_for_onboarding" value="1">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <!-- Individual contract fields for left column -->
                <div class="individual-only" style="display:none">
                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select class="form-control" id="user_id" name="user_id" style="width: 100%;">
                            <option value="">Select a user</option>
                            <!-- Options will be loaded dynamically via AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="engagement_type">Engagement Type</label>
                        <select class="form-control" id="engagement_type" name="engagement_type">
                            <option value="">Select type</option>
                            <option value="consultant">Consultant</option>
                            <option value="employee">Employee</option>
                            <option value="parttime">Part-time</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="duration_type">Duration Type</label>
                        <select class="form-control" id="duration_type" name="duration_type">
                            <option value="">Select duration</option>
                            <option value="days">Days</option>
                            <option value="months">Months</option>
                            <option value="years">Years</option>
                        </select>
                    </div>
                </div>
                <!-- End left column individual fields -->
                <div class="form-group group-only">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group group-only">
                    <label for="number_of_days">Number of Days</label>
                    <input type="number" class="form-control" id="number_of_days" name="number_of_days" required>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Individual contract fields for right column -->
                <div class="individual-only" style="display:none">
                    <div class="form-group">
                        <label for="duration_amount">Duration Time</label>
                        <input type="number" class="form-control" id="duration_amount" name="duration_amount" min="1">
                        <small class="form-text text-muted">Specify the duration value based on the selected type (years, months, or days).</small>
                    </div>
                    <div class="form-group">
                        <label for="ind_start_date">Start Date</label>
                        <input type="date" class="form-control" id="ind_start_date" name="ind_start_date">
                    </div>
                    <div class="form-group">
                        <label for="ind_end_date">End Date</label>
                        <input type="date" class="form-control" id="ind_end_date" name="ind_end_date" readonly>
                    </div>
                    <div class="form-group">
                        <div class="form-group" id="ind_number_of_days_group" style="display:none;">
                            <label for="ind_number_of_days">Number of Working Days (auto, excl. Sundays)</label>
                            <input type="number" class="form-control" id="ind_number_of_days" name="ind_number_of_days" readonly>
                        </div>
                    </div>
                </div>
                <!-- End right column individual fields -->
                <div class="form-group group-only">
                    <label for="role_id">Role</label>
                    <select class="form-control" id="role_id" name="role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group group-only">
                    <label for="counties">Counties</label>
                    <select class="form-control" id="counties" name="counties[]" multiple="multiple">
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="dropped">Dropped</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="project_id">Project</label>
                    <select class="form-control" id="project_id" name="project_id">
                        <option value="">Select a Project</option>
                        @foreach(Vanguard\Projects::orderBy('name')->get() as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
   
    
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="authority-signature-section">
                <h4>Authority Details</h4>
                <div class="authority-fields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="authority_name">Authority Name</label>
                                <input type="text" class="form-control" id="authority_name" name="authority_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="authority_designation">Authority Designation</label>
                                <input type="text" class="form-control" id="authority_designation" name="authority_designation" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="signature">Authority Signature</label>
                    <div id="signature-pad" class="signature-pad">
                        <canvas></canvas>
                    </div>
                    <input type="hidden" name="authority_signature" id="signature-data">
                    <div class="mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="clear-signature">Clear Signature</button>
                    </div>
                </div>
            </div>
        <div class="row mt-4">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary" id="submit-contract">Create Contract</button>
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
        function toggleContractType() {
            var type = document.querySelector('input[name="contract_category"]:checked').value;
            if (type === 'group') {
                document.querySelectorAll('.group-only').forEach(e => e.style.display = 'block');
                document.querySelectorAll('.individual-only').forEach(e => e.style.display = 'none');
                document.getElementById('role_id').required = true;
                document.getElementById('counties').required = true;
                document.getElementById('start_date').required = true;
                document.getElementById('number_of_days').required = true;
                document.getElementById('user_id').required = false;
                // Show group number of days, hide individual
                var indDaysGroup = document.getElementById('ind_number_of_days_group');
                if (indDaysGroup) indDaysGroup.style.display = 'none';
            } else {
                document.querySelectorAll('.group-only').forEach(e => e.style.display = 'none');
                document.querySelectorAll('.individual-only').forEach(e => e.style.display = 'block');
                document.getElementById('role_id').required = false;
                document.getElementById('counties').required = false;
                document.getElementById('start_date').required = false;
                document.getElementById('number_of_days').required = false;
                document.getElementById('user_id').required = true;
                // Hide individual number of days
                var indDaysGroup = document.getElementById('ind_number_of_days_group');
                if (indDaysGroup) indDaysGroup.style.display = 'none';
            }
        }
        document.querySelectorAll('input[name="contract_category"]').forEach(el => {
            el.addEventListener('change', toggleContractType);
        });
        toggleContractType();

        // Auto-calculate number of days (excluding Sundays)
        function calcEndDate() {
            var start = document.getElementById('ind_start_date').value;
            var type = document.getElementById('duration_type').value;
            var amount = parseInt(document.getElementById('duration_amount').value, 10);
            var endInput = document.getElementById('ind_end_date');
            var daysInput = document.getElementById('ind_number_of_days');
            if (!start || !type || !amount || amount < 1) {
                endInput.value = '';
                daysInput.value = '';
                return;
            }
            var startDate = new Date(start);
            var endDate = new Date(startDate);
            if (type === 'days') {
                // Add days, skipping Sundays
                var workingDays = 0;
                while (workingDays < amount) {
                    if (endDate.getDay() !== 0) {
                        workingDays++;
                    }
                    if (workingDays < amount) {
                        endDate.setDate(endDate.getDate() + 1);
                    }
                }
            } else if (type === 'months') {
                endDate.setMonth(endDate.getMonth() + amount);
                endDate.setDate(endDate.getDate() - 1);
            } else if (type === 'years') {
                endDate.setFullYear(endDate.getFullYear() + amount);
                endDate.setDate(endDate.getDate() - 1);
            }
            endInput.value = endDate.toISOString().slice(0,10);
            // Calculate working days (excluding Sundays)
            var s = new Date(startDate), e = new Date(endDate), days = 0;
            while (s <= e) {
                if (s.getDay() !== 0) days++;
                s.setDate(s.getDate() + 1);
            }
            daysInput.value = days;
        }
        document.getElementById('ind_start_date').addEventListener('change', calcEndDate);
        document.getElementById('duration_type').addEventListener('change', calcEndDate);
        document.getElementById('duration_amount').addEventListener('input', calcEndDate);
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
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize Select2 for User dropdown with AJAX
            $('#user_id').select2({
                placeholder: 'Select a user',
                ajax: {
                    url: '/contracts/search-users',
                    dataType: 'json',
                    delay: 500, // Increase delay to 500ms to reduce server load
                    data: function (params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0 // Allow dropdown to fetch data on click without typing
            });

            var canvas = document.querySelector("#signature-pad canvas");
            canvas.width = 400;
            canvas.height = 200;
            var signaturePad = new SignaturePad(canvas);

            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.getElementById('contract-form').addEventListener('submit', function(e) {
    if (signaturePad.isEmpty()) {
        e.preventDefault();
        alert('Please provide a signature');
    } else {
        var signatureData = signaturePad.toDataURL();
        document.getElementById('signature-data').value = signatureData;
    }
});
        });
    </script>
@endsection