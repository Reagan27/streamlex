@extends('layouts.app')

@section('page-title', __('View Field Report'))
@section('page-heading', $report->title)

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <!-- Action Buttons -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('field-reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            <div class="d-flex gap-2">
                @if($report->status !== 'approved')
                    @if($report->created_by === auth()->id() || auth()->user()->hasRole(['Admin', 'Manager']))
                        <a href="{{ route('field-reports.edit', $report) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Report
                        </a>
                        
                        <form action="{{ route('field-reports.destroy', $report) }}" 
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this report?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    @endif
                    @if($report->status === 'submitted' && $report->can_approve)
    <form action="{{ route('field-reports.approve', $report) }}" 
          method="POST">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check me-2"></i>Approve Report
        </button>
    </form>
@endif
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <small class="text-muted d-block">County</small>
                        <h5 class="mb-0">{{ $report->county->name }}</h5>
                    </div>

                    @if($report->content)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Report Content</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! $report->content !!}
                            </div>
                        </div>
                    @endif

                    @if($report->summary)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Summary</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $report->summary }}
                            </div>
                        </div>
                    @endif

                    @if($report->recommendations)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Recommendations</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $report->recommendations }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Report Status</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-{{ $report->status === 'draft' ? 'secondary' : ($report->status === 'submitted' ? 'primary' : 'success') }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Created By</span>
                        <span>{{ $report->creator->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Role</span>
                        <span>{{ $report->creator->role->display_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Created At</span>
                        <span>{{ $report->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($report->approved_by)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Approved By</span>
                            <span>{{ $report->approver->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Approved At</span>
                            <span>{{ $report->approved_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attachments Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attachments</h5>
                </div>
                <div class="card-body">
                    @forelse($report->attachments as $attachment)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
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
                            @if($report->status !== 'approved' && 
                                ($report->created_by === auth()->id() || auth()->user()->hasRole(['Admin', 'Manager'])))
                                <form action="{{ route('field-reports.remove-attachment', [$report->id, $attachment->id]) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to remove this attachment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">No attachments added to this report.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection