@extends('layouts.app')

@section('page-title', 'Back to Office Report: ' . ($report->project_name ?? 'Report'))
@section('page-heading', $report->project_name ?? 'Back to Office Report')

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Back to Office Report</h5>
                    <span class="badge bg-{{ $report->status === 'draft' ? 'secondary' : ($report->status === 'submitted' ? 'primary' : ($report->status === 'rejected' ? 'danger' : 'success')) }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="mb-3 text-primary">PROJECT NAME</h6>
                    <p>{{ $report->project_name ?? 'N/A' }}</p>
                    <hr>
                    <h6 class="mb-3 text-primary">REPORT FIELDS</h6>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Date:</strong> {{ $report->activity_date?->format('M d, Y') ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Activity:</strong> {{ $report->activity ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12"><strong>Venue:</strong> {{ $report->venue ?? 'N/A' }}</div>
                    </div>
                    <hr>
                    <h6 class="mb-3 text-primary">PARTICIPANTS</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>18–35 Years</th>
                                    <th>35–59 Years</th>
                                    <th>Above 60 Years</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['male' => 'Male', 'female' => 'Female', 'disability' => 'Disability', 'vmgs' => 'VMGs'] as $key => $cat)
                                <tr>
                                    <td>{{ $cat }}</td>
                                    @for($i=0; $i<3; $i++)
                                    <td>{{ $report->participants[$key][$i] ?? '' }}</td>
                                    @endfor
                                    <td>{{ $report->participants[$key]['total'] ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h6 class="mb-3 text-primary">NARRATIVE SECTIONS</h6>
                    <div class="mb-3">
                        <strong>Introduction:</strong>
                        <p>{!! nl2br(e($report->introduction)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Objective:</strong>
                        <p>{!! nl2br(e($report->objective)) !!}</p>
                    </div>
                    <hr>
                    <h6 class="mb-3 text-primary">BUDGET / EXPENDITURE</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Planned</th>
                                    <th>Actual</th>
                                    <th>Variance</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($report->budget['item']) && is_array($report->budget['item']))
                                    @foreach($report->budget['item'] as $i => $item)
                                    <tr>
                                        <td>{{ $item }}</td>
                                        <td>{{ $report->budget['planned'][$i] ?? '' }}</td>
                                        <td>{{ $report->budget['actual'][$i] ?? '' }}</td>
                                        <td>{{ $report->budget['variance'][$i] ?? '' }}</td>
                                        <td>{{ $report->budget['comment'][$i] ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <strong>Output:</strong>
                        <p>{!! nl2br(e($report->output)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Key Highlights:</strong>
                        <p>{!! nl2br(e($report->key_highlights)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Challenges & Risks:</strong>
                        <p>{!! nl2br(e($report->challenges_and_risks)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Best Practices:</strong>
                        <p>{!! nl2br(e($report->best_practices)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Lessons Learnt:</strong>
                        <p>{!! nl2br(e($report->lessons_learnt)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Recommendations:</strong>
                        <p>{!! nl2br(e($report->recommendations)) !!}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Way Forward:</strong>
                        <p>{!! nl2br(e($report->way_forward)) !!}</p>
                    </div>
                    <hr>
                </div>
            </div>

            @if($report->attachments->count())
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Annexes ({{ $report->attachments->count() }})</h5>
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
                                @if($attachment->attachment_type)
                                <small class="text-muted ms-2">{{ ucfirst(str_replace('_', ' ', $attachment->attachment_type)) }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Report Info</h5>
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
                    <div class="mb-3">
                        <small class="text-muted">County</small>
                        <p class="mb-0">{{ $report->county->name ?? 'N/A' }}</p>
                    </div>

                    @if(in_array($report->status, ['approved', 'rejected']))
                    <div class="mb-3 pt-2 border-top">
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
                                <div class="d-flex flex-row gap-2 mb-2">
                                    <a href="{{ route('back-to-office-reports.edit', $report) }}" class="btn btn-primary flex-fill">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <form action="{{ route('back-to-office-reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @if($report->status === 'submitted' && $report->can_approve)
                                <form action="{{ route('back-to-office-reports.approve', $report) }}" method="POST" class="d-grid">
                                    @csrf
                                    <label class="form-label small text-muted">Decision Comments</label>
                                    <textarea name="approval_comments" class="form-control mb-2" rows="3" placeholder="Add approval or rejection comments..." required></textarea>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="decision" value="approved" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Approve
                                        </button>
                                        <button type="submit" name="decision" value="rejected" class="btn btn-danger">
                                            <i class="fas fa-times me-2"></i>Reject
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('back-to-office-reports.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        <a href="{{ route('back-to-office-reports.exportPdf', $report) }}" class="btn btn-outline-info w-100 mt-2">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
