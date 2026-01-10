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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="number_of_days">Number of Days</label>
                    <input type="number" class="form-control" id="number_of_days" name="number_of_days" required>
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
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select class="form-control" id="role_id" name="role_id" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="counties">Counties</label>
                    <select class="form-control" id="counties" name="counties[]" multiple="multiple" required>
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
                        <option value="drop">Drop</option>
                    </select>
                </div>
                     <div class="form-group">
    <label for="project_id">Project <span class="text-danger">*</span></label>
    <select class="form-control" id="project_id" name="project_id" required>
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