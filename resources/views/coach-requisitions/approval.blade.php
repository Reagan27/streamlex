@extends('layouts.app')
@section('page-title', 'Requisition Approval')
@section('content')
<div class="container-fluid">
    <h2>Approve/Decline Requisition #{{ $requisition->id }}</h2>

    <div class="card mb-3">
        <div class="card-header">Requisition Details</div>
        <div class="card-body">
            @php
                $statusClass = match($requisition->status) {
                    'approved'     => 'success',
                    'not_approved' => 'danger',
                    'in_review'    => 'warning',
                    'pending'      => 'secondary',
                    default        => 'secondary',
                };
            @endphp
            <div class="row gy-3">
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Title</div>
                    <div class="fw-semibold">{{ $requisition->title }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Date Submitted</div>
                    <div class="fw-semibold">{{ $requisition->created_at->format('Y-m-d') }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Status</div>
                    <div><span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $requisition->status)) }}</span></div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Requested By</div>
                    <div class="fw-semibold">{{ $requisition->user->name }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">County</div>
                    <div class="fw-semibold">{{ $countyName ?? '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Number of Positions</div>
                    <div class="fw-semibold">{{ $requisition->number_of_coaches }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Start Date</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($requisition->start_date)->format('Y-m-d') }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">End Date</div>
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($requisition->end_date)->format('Y-m-d') }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase">Work Arrangement</div>
                    <div class="fw-semibold">{{ ucfirst($requisition->work_arrangement) }}</div>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="text-muted text-uppercase small mb-3">Proposed Positions</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Subcounty Assigned</th>
                            <th>Roles/Responsibilities</th>
                            <th>Justification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisition->proposedCoaches as $coach)
                        <tr>
                            <td>{{ $coach->full_name }}</td>
                            <td>{{ $coach->phone_number }}</td>
                            <td>{{ $coach->email_address }}</td>
                            <td>{{ $coach->sub_county_assigned }}</td>
                            <td>{{ $coach->roles_responsibilities }}</td>
                            <td>{{ $coach->justification }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('coach-requisitions.approval.action', $requisition->id) }}">
        @csrf
        <div class="card mb-3">
            <div class="card-header">Approval Action ({{ ucfirst($approval->role) }})</div>
            <div class="card-body">
                @if($approval->role === 'Finance')
                <div class="mb-3">
                    <label>Budget Line Available:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="budget_line_available" id="budgetYes" value="yes" required>
                        <label class="form-check-label" for="budgetYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="budget_line_available" id="budgetNo" value="no" required>
                        <label class="form-check-label" for="budgetNo">No</label>
                    </div>
                </div>
                <div class="mb-3" id="budgetCodeGroup" style="display:none;">
                    <label for="budgetCode">If Yes, Specify Budget Code:</label>
                    <input type="text" class="form-control" name="budget_code" id="budgetCode">
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const yesRadio = document.getElementById('budgetYes');
                    const noRadio = document.getElementById('budgetNo');
                    const codeGroup = document.getElementById('budgetCodeGroup');
                    yesRadio.addEventListener('change', function() {
                        if (this.checked) codeGroup.style.display = '';
                    });
                    noRadio.addEventListener('change', function() {
                        if (this.checked) codeGroup.style.display = 'none';
                    });
                });
                </script>
                @endif
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" value="approved" id="approveRadio" required>
                    <label class="form-check-label" for="approveRadio">Approve</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" value="not_approved" id="declineRadio" required>
                    <label class="form-check-label" for="declineRadio">Decline</label>
                </div>
                <div class="mt-3">
                    <label>Comments (optional)</label>
                    <textarea class="form-control" name="comments" rows="3"></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('coach-requisitions.show', $requisition->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection