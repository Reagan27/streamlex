@extends('layouts.app')

@section('page-title', __('Create General Report'))
@section('page-heading', __('Create New General Report'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
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
    </style>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <form action="{{ route('general-reports.store') }}" method="POST" enctype="multipart/form-data" id="general-report-form">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Main Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Report Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="daily" {{ old('category') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('category') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('category') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('category') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ old('category') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="occurance" {{ old('category') == 'occurance' ? 'selected' : '' }}>Occurance</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subcategory" class="form-label">Subcategory <span class="text-danger">*</span></label>
                                <select class="form-control @error('subcategory') is-invalid @enderror" id="subcategory" name="subcategory" required>
                                    <option value="">Select Subcategory</option>
                                    <option value="incubation_training" {{ old('subcategory') == 'incubation_training' ? 'selected' : '' }}>Incubation Training</option>
                                    <option value="mentorship" {{ old('subcategory') == 'mentorship' ? 'selected' : '' }}>Mentorship</option>
                                    <option value="coaching" {{ old('subcategory') == 'coaching' ? 'selected' : '' }}>Coaching</option>
                                    <option value="partnership" {{ old('subcategory') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="report" {{ old('subcategory') == 'report' ? 'selected' : '' }}>Report</option>
                                    <option value="minutes" {{ old('subcategory') == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                    <option value="weekly_workplan" {{ old('subcategory') == 'weekly_workplan' ? 'selected' : '' }}>Weekly Workplan</option>
                                </select>
                                @error('subcategory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title/Subject <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="county_id" class="form-label">County <span class="text-danger">*</span></label>
                                <select class="form-control @error('county_id') is-invalid @enderror" 
                                        id="county_id" 
                                        name="county_id" 
                                        required>
                                    <option value="">Select County</option>
                                    @foreach($counties as $county)
                                        <option value="{{ $county->id }}" 
                                                {{ old('county_id') == $county->id ? 'selected' : '' }}>
                                            {{ $county->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('county_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Report Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5">{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control @error('summary') is-invalid @enderror" 
                                      id="summary" 
                                      name="summary" 
                                      rows="3">{{ old('summary') }}</textarea>
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recommendations" class="form-label">Recommendations</label>
                            <textarea class="form-control @error('recommendations') is-invalid @enderror" 
                                      id="recommendations" 
                                      name="recommendations" 
                                      rows="3">{{ old('recommendations') }}</textarea>
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
                        <input type="file" 
                               id="file-upload" 
                               name="attachments[]" 
                               multiple 
                               class="d-none"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

                        <div class="file-upload-area" onclick="document.getElementById('file-upload').click()" style="border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0">Click to upload files</p>
                            <small class="text-muted">Maximum file size: 2MB</small>
                        </div>

                        <div id="file-preview-container" class="mt-3"></div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" name="status" value="draft" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="submitted" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');
    const uploadArea = document.querySelector('.file-upload-area');

    fileUpload.addEventListener('change', handleFileSelect);

    function handleFileSelect(e) {
        const files = this.files;
        for (const file of files) {
            if (file.size > 2 * 1024 * 1024) {
                alert(`File ${file.name} is larger than 2MB`);
                continue;
            }

            const preview = document.createElement('div');
            preview.style.cssText = 'padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;';
            preview.innerHTML = `
                <div>
                    <i class="fas fa-file me-2"></i>
                    <span>${file.name}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-times"></i>
                </button>
            `;

            preview.querySelector('button').onclick = function(e) {
                e.preventDefault();
                preview.remove();
            };

            previewContainer.appendChild(preview);
        }
    }
});
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush
@endsection
