@extends('layouts.app')

@section('page-title', __('Visualization'))
@section('page-heading', __('Visualization'))

@section('content')
<div class="row no-gutters justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="county-productivity-tab" data-toggle="tab" href="#county-productivity" role="tab" aria-controls="county-productivity" aria-selected="true">County Productivity</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="evaluation-tools-tab" data-toggle="tab" href="#evaluation-tools" role="tab" aria-controls="evaluation-tools" aria-selected="false">Evaluation Tools</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="file-upload-tab" data-toggle="tab" href="#file-upload" role="tab" aria-controls="file-upload" aria-selected="false">File Upload</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">
                    <!-- County Productivity (Merged) Tab -->
                    <div class="tab-pane fade show active" id="county-productivity" role="tabpanel" aria-labelledby="county-productivity-tab">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe title="Productivity" class="embed-responsive-item" src="https://app.powerbi.com/view?r=eyJrIjoiMWQwYjEwZjktODljZi00YWUzLThkYzYtOTE4NWNkZDQzMzA1IiwidCI6IjY3YmIxMzU2LWIxYzktNGZlMi1hMDNjLWMxYTU4NWU3MGNjZCJ9" frameborder="0" allowFullScreen="true"></iframe>
                        </div>
                    </div>

                    <!-- Evaluation Tools (Merged) Tab -->
                    <div class="tab-pane fade" id="evaluation-tools" role="tabpanel" aria-labelledby="evaluation-tools-tab">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe title="TPQA Evaluation Tools" class="embed-responsive-item" src="https://app.powerbi.com/view?r=eyJrIjoiNWQzZGJlZDItNTExNS00ZjJlLThkMmQtNDJmYmRhZjhhM2JhIiwidCI6IjY3YmIxMzU2LWIxYzktNGZlMi1hMDNjLWMxYTU4NWU3MGNjZCJ9" frameborder="0" allowFullScreen="true"></iframe>
                        </div>
                    </div>

                    <!-- File Upload Tab -->
                    <div class="tab-pane fade" id="file-upload" role="tabpanel" aria-labelledby="file-upload-tab">
                        <div class="mt-4">
                            <!-- Upload Form Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="mb-0">Upload Excel File</h4>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="file_type">File Type</label>
                                                    <select name="file_type" id="file_type" class="form-control" required>
                                                        <option value="">Select File Type</option>
                                                        <option value="county_specific">County</option>
                                                        <option value="multi_county">Multi-County</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="excel_file">Excel File</label>
                                                    <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx, .xls" required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-2"></i>Upload File
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Uploaded Files</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="files-table">
                                            <thead>
                                                <tr>
                                                    <th>File Name</th>
                                                    <th>Type</th>
                                                    <th>Records</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($excelData as $file)
                                                    <tr>
                                                    <td>{{ $file->original_filename ?? $file->file_identifier }}</td>
                                                        <td>
                                                            <span class="badge {{ $file->file_type === 'county_specific' ? 'badge-info' : 'badge-success' }}">
                                                                {{ $file->file_type === 'county_specific' ? 'County' : 'Multi-County' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $file->record_count }} records</td>
                                                        <td>
                                                            <form action="{{ route('delete.file') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="file_identifier" value="{{ $file->file_identifier }}">
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file? This will delete all records associated with this file.')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 85%;
    }
    .table td {
        vertical-align: middle;
    }
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }
    .nav-tabs .nav-link.active {
        border-color: #dee2e6 #dee2e6 #fff;
        border-bottom-width: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#files-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search files..."
        }
    });

    const activeTab = localStorage.getItem('activeVisualizationTab');
    if (activeTab) {
        const tab = document.querySelector(activeTab);
        if (tab) {
            tab.click();
        }
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('activeVisualizationTab', '#' + e.target.id);
    });
});
</script>
@endpush

