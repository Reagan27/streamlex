@extends('layouts.app')
@section('page-title', 'Requisition Details')

@section('content')
<div class="container">
    <h2>Requisition #{{ $requisition->id }}</h2>

    <div class="card mb-3">
        <div class="card-header">Request Details</div>
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
    </div>
</div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Proposed Requisition</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Sub-County Assigned</th>
                        <th>Justification</th>
                        <th>Roles & Responsibilities</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisition->proposedCoaches as $coach)
                    <tr>
                        <td>{{ $coach->full_name }}</td>
                        <td>{{ $coach->sub_county_assigned }}</td>
                        <td>
                            @if($coach->justification)
                                {{ $coach->justification }}
                            @else
                                <span class="text-muted">No justification provided</span>
                            @endif
                        </td>
                        <td>{{ $coach->roles_responsibilities }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Approvals</div>
        <div class="card-body">
            <link rel="stylesheet" href="/css/stepper.css?v=2">
            <div id="approval-stepper" class="stepper" style="display:flex !important;flex-direction:row !important;align-items:flex-start !important;gap:2rem !important;justify-content:center !important;width:100%;overflow-x:auto;"></div>
            <div id="approval-step-details" class="mt-3"></div>
            <div class="d-flex justify-content-between mt-3">
                <button id="stepper-back" class="btn btn-outline-secondary btn-sm">Back</button>
                <button id="stepper-next" class="btn btn-outline-primary btn-sm">Next</button>
            </div>
            @php
                $roleDisplayMap = \Vanguard\Role::pluck('display_name', 'name')->toArray();
            @endphp
            <script>
                const roleDisplayMap = @json($roleDisplayMap);
                // Step 1 is always Regional_Coordinator. Step 2 is whichever of
                // Manager/Finance/Admin actually has a row for this requisition
                // (rows are created per-eligible-role; only the one that acts matters).
                const approvals = @json($requisition->approvals->values());

                let steps = [];

                // Step 1: Regional_Coordinator (may not exist if requester WAS the regional coordinator)
                let ccApproval = approvals.find(a => a.role === 'Regional_Coordinator');
                steps.push(ccApproval || { status: 'skipped', approval_level: 1, role: 'Regional_Coordinator', approver_name: null, comments: null });

                // Step 2: collapse Manager/Finance/Admin rows into a single step.
                // Show whichever one is approved/not_approved; otherwise show as pending
                // with a combined label.
                let finalRoles = ['Manager', 'Finance', 'Admin'];
                let finalApprovals = approvals.filter(a => finalRoles.includes(a.role));
                let decided = finalApprovals.find(a => a.status === 'approved' || a.status === 'not_approved');

                if (decided) {
                    steps.push(decided);
                } else {
                    steps.push({
                        status: 'pending',
                        approval_level: 2,
                        role: 'Manager/Finance/Admin',
                        approver_name: null,
                        comments: null
                    });
                }

                let currentStep = steps.findIndex(a => a.status === 'pending');
                if (currentStep === -1) currentStep = steps.length - 1;

                function renderStepper() {
                    let html = '';
                    steps.forEach((a, i) => {
                        let status = a.status.toLowerCase();
                        let isCompleted = status === 'approved';
                        let isPending = status === 'pending';
                        let circle, circleClass = '', connectorClass = '';

                        if (isCompleted) {
                            circle = '<i class="bi bi-check-lg"></i>';
                            circleClass = 'stepper-circle stepper-circle-green';
                            connectorClass = 'stepper-connector stepper-connector-green';
                        } else if (isPending && i === currentStep) {
                            circle = (i + 1);
                            circleClass = 'stepper-circle stepper-circle-orange';
                            connectorClass = 'stepper-connector';
                        } else {
                            circle = (i + 1);
                            circleClass = 'stepper-circle stepper-circle-gray';
                            connectorClass = 'stepper-connector';
                        }

                        let label = a.role === 'Manager/Finance/Admin'
                        ? 'Manager / Finance / Admin'
                        : (roleDisplayMap[a.role] || a.role.replace(/_/g, ' '));
                        html += `<div class="stepper-step" data-step="${i}">
                            <div class="${circleClass}">${circle}</div>
                            <div class="stepper-label">${label}</div>
                        </div>`;
                        if (i < steps.length - 1) html += `<div class="${connectorClass}" data-conn="${i}"></div>`;
                    });

                    document.getElementById('approval-stepper').innerHTML = html;

                    document.querySelectorAll('.stepper-step').forEach((el, idx) => {
                        el.classList.toggle('selected', idx === currentStep);
                        el.onclick = () => {
                            currentStep = idx;
                            renderStepper();
                            renderStepDetails();
                        };
                    });

                    document.querySelectorAll('.stepper-connector').forEach((el, idx) => {
                        if (steps[idx].status.toLowerCase() === 'approved') {
                            el.classList.add('stepper-connector-green');
                        } else {
                            el.classList.remove('stepper-connector-green');
                        }
                    });
                }

                function renderStepDetails() {
                    const a = steps[currentStep];
                    let statusColor = a.status === 'approved' ? '#22c55e' : a.status === 'pending' ? '#f59e42' : '#9ca3af';
                    let statusText = a.status === 'approved' ? 'APPROVED' : 'PENDING';

                    if (a.status === 'not_approved') {
                        statusText = 'NOT APPROVED';
                        statusColor = '#ef4444';
                    }

                    let label = a.role === 'Manager/Finance/Admin'
                    ? 'Manager / Finance / Admin'
                    : (roleDisplayMap[a.role] || a.role.replace(/_/g, ' '));
                    let html = `<div style="font-size:1.1rem;font-weight:600;color:${statusColor}">Status: <span style="color:${statusColor}">${statusText}</span></div>`;
                    html += `<div class="mt-1">Level <b>${a.approval_level}</b> - <span class="fw-semibold">${label}</span></div>`;
                    html += `<div class="mt-1">Approver: <b>${a.approver_name ?? '—'}</b></div>`;
                    if (a.comments) html += `<div class="mt-2 text-secondary">"${a.comments}"</div>`;
                    document.getElementById('approval-step-details').innerHTML = html;
                }

                document.getElementById('stepper-back').onclick = function() {
                    if (currentStep > 0) {
                        currentStep--;
                        renderStepper();
                        renderStepDetails();
                    }
                };

                document.getElementById('stepper-next').onclick = function() {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        renderStepper();
                        renderStepDetails();
                    }
                };

                renderStepper();
                renderStepDetails();
            </script>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Field Activities <span class="badge bg-secondary">{{ $requisition->fieldActivities->count() }}</span></h5>
            @if($requisition->status === 'approved')
                <a href="{{ route('field-activities.create', ['requisition_id' => $requisition->id]) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Create Field Activity
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($requisition->fieldActivities->isEmpty())
                <p class="text-muted mb-0">No field activities yet.</p>
            @else
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th>Budget (KES)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisition->fieldActivities as $fa)
                        <tr>
                            <td>{{ $fa->title }}</td>
                            <td>{{ ucfirst($fa->status) }}</td>
                            <td>{{ $fa->start_date }} - {{ $fa->end_date }}</td>
                            <td>{{ number_format($fa->total_planned_budget, 2) }}</td>
                            <td><a href="{{ route('field-activities.show', $fa->id) }}" class="btn btn-info btn-sm">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <a href="{{ route('coach-requisitions.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>

@if(session('success'))
<script>
    alert(@json(session('success')));
    window.location = @json(route('coach-requisitions.index'));
</script>
@endif
@endsection
