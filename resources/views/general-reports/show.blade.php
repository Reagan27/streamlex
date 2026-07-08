@extends('layouts.app')

@section('page-title', 'General Report: ' . $report->title)
@section('page-heading', $report->title)

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $report->title }}</h5>
                            <p class="text-muted">County: {{ $report->county->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-{{ $report->status === 'draft' ? 'secondary' : ($report->status === 'submitted' ? 'primary' : ($report->status === 'rejected' ? 'danger' : 'success')) }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="text-muted mb-1">Category: {{ ucfirst($report->category) }}</p>
                            <p class="text-muted mb-0">Subcategory: {{ $report->subcategory ? ucfirst(str_replace(['_', '-'], ' ', $report->subcategory)) : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($report->content)
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-2">Content</h6>
                        <div>{!! $report->content !!}</div>
                    </div>
                    @endif

                    @if($report->summary)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="text-muted mb-2">Summary</h6>
                        <p>{{ $report->summary }}</p>
                    </div>
                    @endif

                    @if($report->recommendations)
                    <div class="border-top pt-3 mt-3">
                        <h6 class="text-muted mb-2">Recommendations</h6>
                        <p>{{ $report->recommendations }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($report->attachments->count())
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attachments ({{ $report->attachments->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($report->attachments as $attachment)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file me-2"></i>
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                    {{ $attachment->original_name }}
                                </a>
                               <small class="text-muted ms-2">{{ number_format($attachment->file_size / 1048576, 2) }} MB</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Report Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created By</small>
                        <p class="mb-0">{{ $report->creator->name }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Created At</small>
                        <p class="mb-0">{{ $report->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    @if(in_array($report->status, ['approved', 'rejected']))
                    <div class="mb-3">
                        <small class="text-muted">{{ $report->status === 'approved' ? 'Approved' : 'Rejected' }} By</small>
                        <p class="mb-0">{{ $report->approver->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">{{ $report->status === 'approved' ? 'Approved' : 'Rejected' }} At</small>
                        <p class="mb-0">{{ $report->approved_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                    @if($report->approval_comments)
                    <div class="mb-3">
                        <small class="text-muted">Decision Comments</small>
                        <p class="mb-0">{{ $report->approval_comments }}</p>
                    </div>
                    @endif
                    @endif

                    <hr>

                    <div class="d-grid gap-2">
                        @if($report->status !== 'approved')
                            @if($report->created_by === auth()->id() || auth()->user()->hasRole(['Admin', 'Manager']))
                                <a href="{{ route('general-reports.edit', $report) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Report
                                </a>

                                <form action="{{ route('general-reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Delete Report
                                    </button>
                                </form>
                            @endif

                            @if($report->status === 'submitted' && $report->can_approve)
                                <form action="{{ route('general-reports.approve', $report) }}" method="POST" class="d-grid">
                                    @csrf
                                    <label class="form-label small text-muted">Decision Comments</label>
                                    <textarea name="approval_comments" class="form-control mb-2" rows="3" placeholder="Add approval or rejection comments..." required></textarea>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="decision" value="approved" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Approve Report
                                        </button>
                                        <button type="submit" name="decision" value="rejected" class="btn btn-danger">
                                            <i class="fas fa-times me-2"></i>Reject Report
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('general-reports.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
