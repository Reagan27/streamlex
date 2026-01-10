@extends('layouts.app')

@section('page-title', __('Edit Field Report'))
@section('page-heading', __('Edit Field Report'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .file-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-upload-area:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
        .attachment-preview {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .existing-file {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <form action="{{ route('field-reports.update', $report) }}" method="POST" enctype="multipart/form-data" id="field-report-form">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Main Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Report Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Title/Subject <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $report->title) }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
    <label for="county_id" class="form-label">County <span class="text-danger">*</span></label>
    <select class="form-control select2 @error('county_id') is-invalid @enderror" 
            id="county_id" 
            name="county_id" 
            required
            {{ !auth()->user()->isAdmin() && !auth()->user()->hasRole('Regional_Coordinator') ? 'disabled' : '' }}>
        <option value="">Select County</option>
        @foreach($counties as $county)
            <option value="{{ $county->id }}" 
                    {{ old('county_id', $report->county_id) == $county->id ? 'selected' : '' }}>
                {{ $county->name }}
            </option>
        @endforeach
    </select>
    @if(!auth()->user()->isAdmin() && !auth()->user()->hasRole('Regional_Coordinator'))
        <input type="hidden" name="county_id" value="{{ auth()->user()->county_id }}">
    @endif
    @error('county_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Report Content</label>
                            <textarea class="form-control" id="content" name="content">{{ old('content', $report->content) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                      id="summary" 
                                      name="summary" 
                                      rows="3">{{ old('summary', $report->summary) }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recommendations" class="form-label">Recommendations</label>
                            <textarea class="form-control @error('recommendations') is-invalid @enderror" 
                                      id="recommendations" 
                                      name="recommendations" 
                                      rows="3">{{ old('recommendations', $report->recommendations) }}</textarea>
                            @error('recommendations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- File Upload Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attachments</h5>
                    </div>
                    <div class="card-body">
                        <!-- Existing Files -->
                        @if($report->attachments->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Current Files</h6>
                                @foreach($report->attachments as $attachment)
                                    <div class="attachment-preview existing-file">
                                        <div>
                                            <i class="fas fa-file me-2"></i>
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               target="_blank"
                                               class="text-decoration-none">
                                                {{ $attachment->original_name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{ number_format($attachment->file_size / 1024, 2) }} KB
                                            </small>
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger remove-attachment"
                                                data-attachment-id="{{ $attachment->id }}"
                                                title="Remove File">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- New File Upload -->
                        <input type="file" 
                               id="file-upload" 
                               name="attachments[]" 
                               multiple 
                               class="d-none"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

                        <div class="file-upload-area" onclick="document.getElementById('file-upload').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0">Click to upload new files or drag and drop</p>
                            <small class="text-muted">Maximum file size: 2MB</small>
                        </div>

                        <div id="file-preview-container" class="mt-3"></div>

                        @error('attachments')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" name="status" value="draft" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="submitted" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                        <a href="{{ route('field-reports.show', $report) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize Summernote
    $('#content').summernote({
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

    // Handle existing attachment removal
    document.querySelectorAll('.remove-attachment').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('Are you sure you want to remove this file?')) {
                const attachmentId = this.dataset.attachmentId;
                fetch(`/field-reports/${@json($report->id)}/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.closest('.attachment-preview').remove();
                    } else {
                        alert('Failed to remove file. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

    // File Upload Handling
    const fileUpload = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');
    const uploadArea = document.querySelector('.file-upload-area');

    fileUpload.addEventListener('change', handleFileSelect);

    // Drag and drop handling
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#007bff';
        uploadArea.style.backgroundColor = '#f8f9fa';
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.backgroundColor = '';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.backgroundColor = '';
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    function handleFileSelect(e) {
        handleFiles(this.files);
    }

    function handleFiles(files) {
        for (const file of files) {
            if (file.size > 2 * 1024 * 1024) {
                alert(`File ${file.name} is larger than 2MB`);
                continue;
            }

            const preview = document.createElement('div');
            preview.className = 'attachment-preview';
            preview.innerHTML = `
                <div>
                    <i class="fas fa-file me-2"></i>
                    <span>${file.name}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-times"></i>
                </button>
            `;

            preview.querySelector('button').onclick = function() {
                preview.remove();
            };

            previewContainer.appendChild(preview);
        }
    }
});
</script>
@endpush