@extends('layouts.app')

@section('page-title', 'Edit General Report')
@section('page-heading', 'Edit General Report')

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

    <form action="{{ route('general-reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
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
                                    <option value="daily" {{ old('category', $report->category) == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('category', $report->category) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('category', $report->category) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('category', $report->category) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ old('category', $report->category) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="occurance" {{ old('category', $report->category) == 'occurance' ? 'selected' : '' }}>Occurance</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subcategory" class="form-label">Subcategory <span class="text-danger">*</span></label>
                                <select class="form-control @error('subcategory') is-invalid @enderror" id="subcategory" name="subcategory" required>
                                    <option value="">Select Subcategory</option>
                                    <option value="incubation_training" {{ old('subcategory', $report->subcategory) == 'incubation_training' ? 'selected' : '' }}>Incubation Training</option>
                                    <option value="mentorship" {{ old('subcategory', $report->subcategory) == 'mentorship' ? 'selected' : '' }}>Mentorship</option>
                                    <option value="coaching" {{ old('subcategory', $report->subcategory) == 'coaching' ? 'selected' : '' }}>Coaching</option>
                                    <option value="partnership" {{ old('subcategory', $report->subcategory) == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="report" {{ old('subcategory', $report->subcategory) == 'report' ? 'selected' : '' }}>Report</option>
                                    <option value="minutes" {{ old('subcategory', $report->subcategory) == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                    <option value="weekly_workplan" {{ old('subcategory', $report->subcategory) == 'weekly_workplan' ? 'selected' : '' }}>Weekly Workplan</option>
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
                                       value="{{ old('title', $report->title) }}" 
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
                                                {{ old('county_id', $report->county_id) == $county->id ? 'selected' : '' }}>
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
                            <textarea class="form-control" id="content" name="content" rows="5">{{ old('content', $report->content) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" id="summary" name="summary" rows="3">{{ old('summary', $report->summary) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="recommendations" class="form-label">Recommendations</label>
                            <textarea class="form-control" id="recommendations" name="recommendations" rows="3">{{ old('recommendations', $report->recommendations) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attachments</h5>
                    </div>
                    <div class="card-body">
                        @if($report->attachments->count())
                        <div class="mb-3" id="existing-attachments">
                            <h6 class="mb-2">Current Attachments</h6>
                            @foreach($report->attachments as $attachment)
                            <div id="attachment-{{ $attachment->id }}" style="padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <i class="fas fa-file me-2"></i>
                                    <span>{{ Str::limit($attachment->original_name, 20) }}</span>
                                </div>
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="removeAttachment({{ $report->id }}, {{ $attachment->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="file" 
                               id="file-upload" 
                               name="attachments[]" 
                               multiple 
                               class="d-none">

                        <div class="file-upload-area" onclick="document.getElementById('file-upload').click()" style="border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0">Click to add files</p>
                            <small class="text-muted">Maximum file size: 2MB</small>
                        </div>

                        <div id="file-preview-container" class="mt-3"></div>
                    </div>
                </div>

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
function removeAttachment(reportId, attachmentId) {
    if (!confirm('Remove this attachment?')) return;

    fetch(`/general-reports/${reportId}/attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const el = document.getElementById(`attachment-${attachmentId}`);
            if (el) el.remove();
        } else {
            alert(data.message || 'Failed to remove attachment.');
        }
    })
    .catch(() => alert('Failed to remove attachment.'));
}

document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('file-upload');
    const previewContainer = document.getElementById('file-preview-container');

    fileUpload.addEventListener('change', function() {
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

            preview.querySelector('button').onclick = function() {
                preview.remove();
            };

            previewContainer.appendChild(preview);
        }
    });
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

        const fileUpload = document.getElementById('file-upload');
        const previewContainer = document.getElementById('file-preview-container');

        fileUpload.addEventListener('change', function() {
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

                preview.querySelector('button').onclick = function() {
                    preview.remove();
                };

                previewContainer.appendChild(preview);
            }
        });
    });
</script>
@endpush
@endsection