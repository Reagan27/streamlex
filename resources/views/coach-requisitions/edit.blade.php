@extends('layouts.app')
@section('page-title', 'Edit Requisition')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Edit Requisition</h2>
        <a href="{{ route('coach-requisitions.show', $requisition->id) }}" class="btn btn-secondary btn-sm">Cancel</a>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('coach-requisitions.update', $requisition->id) }}" id="requisitionForm">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" name="title"
                        value="{{ old('title', $requisition->title) }}"
                        placeholder="Enter a descriptive title" required>
                </div>
            </div>

            {{-- Engagement Type --}}
            <h5 class="mt-3 mb-3">Request Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Engagement Type</label>
                    <select class="form-select" name="engagement_type" id="engagement-type" required onchange="updateEngagementFields()">
                        <option value="">Select type...</option>
                        <option value="hourly" {{ old('engagement_type', $requisition->engagement_type) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                        <option value="daily"  {{ old('engagement_type', $requisition->engagement_type) === 'daily'  ? 'selected' : '' }}>Daily</option>
                        <option value="fixed"  {{ old('engagement_type', $requisition->engagement_type) === 'fixed'  ? 'selected' : '' }}>Fixed</option>
                    </select>
                </div>
                <div class="col-md-12" id="engagement-fields"></div>
                <input type="hidden" name="date_of_request" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="requested_by_name" value="{{ $user->name }}">
            </div>

            {{-- Position Details --}}
            <h5 class="mt-3 mb-3">Position Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label>Position Title</label>
                    @php
                        if (in_array($user->role->name, ['Admin', 'Manager'])) {
                            $assignableRoles = $roles;
                        } else {
                            $service = app(\Vanguard\Services\RoleHierarchyService::class);
                            $assignableNames = $service->getAllSubordinateRoles($user->role->name);
                            $assignableRoles = $roles->filter(function($r) use ($assignableNames) {
                                return in_array($r->name, $assignableNames);
                            });
                        }
                    @endphp
                    <select class="form-select" name="position_title" id="positionTitleSelect" required>
                        <option value="">-- Select Role --</option>
                        @foreach($assignableRoles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('position_title', $requisition->position_title) === $role->name ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                        @if(in_array($user->role->name, ['Admin', 'Manager']))
                            <option value="all" {{ old('position_title', $requisition->position_title) === 'all' ? 'selected' : '' }}>All</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Number of Positions Required</label>
                    <input type="number" class="form-control" name="number_of_coaches" min="1" required
                        value="{{ old('number_of_coaches', $requisition->number_of_coaches) }}">
                </div>
                <div class="col-md-6">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="start_date" required
                        value="{{ old('start_date', \Carbon\Carbon::parse($requisition->start_date)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label>End Date</label>
                    <input type="date" class="form-control" name="end_date" required
                        value="{{ old('end_date', \Carbon\Carbon::parse($requisition->end_date)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label>Work Arrangement</label>
                    <select class="form-select" name="work_arrangement" required>
                        <option value="field"  {{ old('work_arrangement', $requisition->work_arrangement) === 'field'  ? 'selected' : '' }}>Field-Based</option>
                        <option value="office" {{ old('work_arrangement', $requisition->work_arrangement) === 'office' ? 'selected' : '' }}>Office-Based</option>
                        <option value="hybrid" {{ old('work_arrangement', $requisition->work_arrangement) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
                <input type="hidden" name="reporting_to"
                    value="{{ $supervisors->first() ? $supervisors->first()->first_name . ' ' . $supervisors->first()->last_name : '' }}">
            </div>

            {{-- Proposed Coaches Table --}}
            <h5 class="mt-3 mb-3">List of Proposed Positions</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered" id="proposedCoachesTable">
                    <thead>
                        <tr>
                            <th>Coach</th>
                            <th>Justification</th>
                            <th>Roles &amp; Responsibilities</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" class="btn btn-secondary btn-sm mt-2" id="addCoachBtn">
                    <i class="fas fa-plus"></i> Add Position
                </button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Requisition</button>
                <a href="{{ route('coach-requisitions.show', $requisition->id) }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

@php
    // Build usersByRole
    $rolesAll = \Vanguard\Role::all();
    $usersByRoleData = [];
    $currentRole = $user->role ? $user->role->name : null;
    $currentCountyId = $user->county_id ?? null;
    $currentSubcountyId = $user->subcounty_id ?? null;
    $roleHierarchyMap = [
        'Admin'    => ['Regional_Coordinator','County_Coordinator','Supervisor','Field_Officer','User','Finance','Manager','Partnerships_Growth_Manager'],
        'Manager'  => ['Regional_Coordinator','County_Coordinator','Supervisor','Field_Officer','User','Finance','Partnerships_Growth_Manager'],
        'Regional_Coordinator' => ['County_Coordinator','Supervisor','Field_Officer','User'],
        'County_Coordinator'   => ['Supervisor','Field_Officer','User'],
        'Supervisor'   => ['Field_Officer','User'],
        'Field_Officer'=> ['User'],
        'User' => [], 'Finance' => [], 'Partnerships_Growth_Manager' => []
    ];
    foreach ($rolesAll as $roleItem) {
        if (in_array($currentRole, ['Admin','Manager'])) {
            $usersByRoleData[$roleItem->name] = \Vanguard\User::with('subcounty')
                ->where('role_id', $roleItem->id)->get()
                ->map(function($u) {
                    return ['id' => $u->id, 'name' => $u->first_name.' '.$u->last_name,
                            'phone' => $u->phone ?? '', 'email' => $u->email ?? '',
                            'sub_county' => optional($u->subcounty)->name ?? ''];
                })->values();
        } else {
            $subordinateRoles = $roleHierarchyMap[$currentRole] ?? [];
            if (in_array($roleItem->name, $subordinateRoles)) {
                $q = \Vanguard\User::with('subcounty')->where('role_id', $roleItem->id);
                if (in_array($currentRole, ['Regional_Coordinator','County_Coordinator'])) {
                    $q->where('county_id', $currentCountyId);
                } elseif ($currentRole === 'Supervisor') {
                    $q->where('subcounty_id', $currentSubcountyId);
                }
                $usersByRoleData[$roleItem->name] = $q->get()->map(function($u) {
                    return ['id' => $u->id, 'name' => $u->first_name.' '.$u->last_name,
                            'phone' => $u->phone ?? '', 'email' => $u->email ?? '',
                            'sub_county' => optional($u->subcounty)->name ?? ''];
                })->values();
            } else {
                $usersByRoleData[$roleItem->name] = collect();
            }
        }
    }
    $usersByRoleData['all'] = in_array($currentRole, ['Admin','Manager'])
        ? \Vanguard\User::with('subcounty')->get()->map(function($u) {
            return ['id' => $u->id, 'name' => $u->first_name.' '.$u->last_name,
                    'phone' => $u->phone ?? '', 'email' => $u->email ?? '',
                    'sub_county' => optional($u->subcounty)->name ?? ''];
          })->values()
        : collect();

    // Build existing coaches array
    $existingCoachesData = $requisition->proposedCoaches->map(function($c) {
        return [
            'full_name'              => $c->full_name,
            'phone_number'           => $c->phone_number,
            'email_address'          => $c->email_address,
            'sub_county_assigned'    => $c->sub_county_assigned,
            'roles_responsibilities' => $c->roles_responsibilities,
            'justification'          => $c->justification,
            'coach_user_id'          => $c->coach_user_id,
        ];
    })->values()->toArray();

    $engType  = $requisition->engagement_type;
    $engRate  = $requisition->engagement_rate;
    $engTotal = $requisition->engagement_total;
@endphp

<script>
const loggedInRole          = @json($user->role ? $user->role->name : '');
const usersByRole            = @json($usersByRoleData);
const existingCoaches        = @json($existingCoachesData);
const existingEngagementType = @json($engType);
const existingEngagementRate = @json($engRate);
const existingEngagementTotal= @json($engTotal);

let rowCounter = 0;

function getSelectedRole() {
    const positionSelect = document.getElementById('positionTitleSelect');
    let selectedRole = positionSelect ? positionSelect.value : '';
    if (!selectedRole) selectedRole = 'all';
    return selectedRole;
}

function getUserOptions(roleOverride) {
    let selectedRole = roleOverride || getSelectedRole();
    let users = (loggedInRole === 'Admin' || loggedInRole === 'Manager') && selectedRole === 'all'
        ? (usersByRole['all'] || [])
        : (usersByRole[selectedRole] || []);
    let options = '<option value="">-- Select Coach --</option>';
    if (!users || users.length === 0) {
        options += '<option value="" disabled>No users available for this role and location</option>';
    } else {
        users.forEach(u => {
            if (u.id && u.email) {
                options += `<option value="${u.name}" data-user-id="${u.id}" data-phone="${u.phone}" data-email="${u.email}" data-subcounty="${u.sub_county}">${u.name}</option>`;
            }
        });
    }
    return options;
}

const justificationValues = [
    'Increased workload',
    'New project activities',
    'Staff shortage',
    'Specialized skills requirement',
    'Temporary assignment'
];

function createCoachRow(existing) {
    const rowIndex = rowCounter++;
    const tr = document.createElement('tr');
    let options = getUserOptions();

    let checkedValues = [];
    let otherText = '';
    if (existing && existing.justification) {
        const parts = existing.justification.split(', ');
        parts.forEach(part => {
            if (part.startsWith('Other: ')) {
                otherText = part.replace('Other: ', '');
            } else if (justificationValues.includes(part)) {
                checkedValues.push(part);
            }
        });
    }

    tr.innerHTML = `
        <td>
            <select class="form-select coach-select" name="coach_full_name[]" required>
                ${options}
            </select>
            <input type="hidden" class="coach-phone"     name="coach_phone_number[]"        value="${existing ? (existing.phone_number || '') : ''}">
            <input type="hidden" class="coach-email"     name="coach_email_address[]"       value="${existing ? (existing.email_address || '') : ''}">
            <input type="hidden" class="coach-subcounty" name="coach_sub_county_assigned[]" value="${existing ? (existing.sub_county_assigned || '') : ''}">
            <input type="hidden" class="coach-user-id"   name="coach_user_id[]"             value="${existing ? (existing.coach_user_id || '') : ''}">
        </td>
        <td>
            ${justificationValues.map(val => `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="${val}" ${checkedValues.includes(val) ? 'checked' : ''}>
                <label class="form-check-label">${val}</label>
            </div>`).join('')}
            <div class="form-check">
                <input class="form-check-input other-checkbox" type="checkbox" name="justification[${rowIndex}][]" value="Other" ${otherText ? 'checked' : ''}>
                <label class="form-check-label">Other (Specify)</label>
            </div>
            <div class="other-text-wrapper" style="display:${otherText ? 'block' : 'none'}; margin-top:6px;">
                <textarea class="form-control" name="justification_other[${rowIndex}]" rows="2" placeholder="Please specify...">${otherText}</textarea>
            </div>
        </td>
        <td>
            <textarea class="form-control" name="roles_responsibilities[]" rows="2" placeholder="Key duties...">${existing ? (existing.roles_responsibilities || '') : ''}</textarea>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-coach" title="Remove"><i class="fas fa-trash"></i></button>
        </td>
    `;

    if (existing && existing.full_name) {
        const select = tr.querySelector('.coach-select');
        let found = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === existing.full_name) {
                select.selectedIndex = i;
                found = true;
                break;
            }
        }
        if (!found) {
            const opt = document.createElement('option');
            opt.value = existing.full_name;
            opt.text  = existing.full_name;
            opt.setAttribute('data-phone',     existing.phone_number    || '');
            opt.setAttribute('data-email',     existing.email_address   || '');
            opt.setAttribute('data-subcounty', existing.sub_county_assigned || '');
            opt.setAttribute('data-user-id',   existing.coach_user_id   || '');
            opt.selected = true;
            select.appendChild(opt);
        }
    }

    tr.querySelector('.coach-select').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        tr.querySelector('.coach-phone').value     = selected.dataset.phone     || '';
        tr.querySelector('.coach-email').value     = selected.dataset.email     || '';
        tr.querySelector('.coach-subcounty').value = selected.dataset.subcounty || '';
        tr.querySelector('.coach-user-id').value   = selected.dataset.userId    || '';
    });

    const otherCheckbox = tr.querySelector('.other-checkbox');
    const otherWrapper  = tr.querySelector('.other-text-wrapper');
    otherCheckbox.addEventListener('change', function () {
        otherWrapper.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) otherWrapper.querySelector('textarea').value = '';
    });

    tr.querySelector('.remove-coach').addEventListener('click', function () {
        tr.remove();
        updateCoachCount();
        updateEngagementTotal();
        reindexCoachRows();
    });

    return tr;
}

function reindexCoachRows() {
    document.querySelectorAll('#proposedCoachesTable tbody tr').forEach(function(row, idx) {
        row.querySelectorAll('input[type="checkbox"]').forEach(cb => { cb.name = `justification[${idx}][]`; });
        const otherTextarea = row.querySelector('.other-text-wrapper textarea');
        if (otherTextarea) otherTextarea.name = `justification_other[${idx}]`;
    });
}

function updateCoachCount() {
    const count = document.querySelectorAll('#proposedCoachesTable tbody tr').length;
    const numberInput = document.querySelector('input[name="number_of_coaches"]');
    if (numberInput) numberInput.value = count > 0 ? count : 1;
}

function updateEngagementFields() {
    const type   = document.getElementById('engagement-type').value;
    const fields = document.getElementById('engagement-fields');
    fields.innerHTML = '';
    if (!type) return;
    let html = '';
    if (type === 'hourly') {
        html = `<div class='row g-2'>
            <div class='col-md-4 mb-2'><label class='form-label'>Rate per Hour (KES)</label><input type='number' class='form-control' name='engagement_rate' id='rate-hour' min='0' oninput='updateEngagementTotal()' required></div>
            <div class='col-md-4 mb-2'><label class='form-label'>Total Hours</label><input type='number' class='form-control' name='engagement_hours' id='total-hours' readonly></div>
            <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
        </div>`;
    } else if (type === 'daily') {
        html = `<div class='row g-2'>
            <div class='col-md-4 mb-2'><label class='form-label'>Rate per Day (KES)</label><input type='number' class='form-control' name='engagement_rate' id='rate-day' min='0' oninput='updateEngagementTotal()' required></div>
            <div class='col-md-4 mb-2'><label class='form-label'>Total Days</label><input type='number' class='form-control' name='engagement_days' id='total-days' readonly></div>
            <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
        </div>`;
    } else if (type === 'fixed') {
        html = `<div class='row g-2'>
            <div class='col-md-4 mb-2'><label class='form-label'>Fixed Total Amount (KES)</label><input type='number' class='form-control' name='engagement_rate' id='fixed-amount' min='0' oninput='updateEngagementTotal()' required></div>
            <div class='col-md-4 mb-2'></div>
            <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
        </div>`;
    }
    fields.innerHTML = html;

    if (type === 'hourly' && document.getElementById('rate-hour'))    document.getElementById('rate-hour').value    = existingEngagementRate;
    if (type === 'daily'  && document.getElementById('rate-day'))     document.getElementById('rate-day').value     = existingEngagementRate;
    if (type === 'fixed'  && document.getElementById('fixed-amount')) document.getElementById('fixed-amount').value = existingEngagementRate;

    updateEngagementTotal();

    document.querySelector('input[name="start_date"]').addEventListener('change', updateEngagementTotal);
    document.querySelector('input[name="end_date"]').addEventListener('change', updateEngagementTotal);
}

function updateEngagementTotal() {
    const type  = document.getElementById('engagement-type').value;
    const start = document.querySelector('input[name="start_date"]').value;
    const end   = document.querySelector('input[name="end_date"]').value;
    let total   = 0;

    if (type === 'hourly') {
        const rate = parseFloat(document.getElementById('rate-hour')?.value) || 0;
        let hours = (start && end) ? Math.max(0, Math.floor((new Date(end) - new Date(start)) / (1000*60*60))) : 0;
        if (document.getElementById('total-hours')) document.getElementById('total-hours').value = hours;
        total = rate * hours;
    } else if (type === 'daily') {
        const rate = parseFloat(document.getElementById('rate-day')?.value) || 0;
        let days = (start && end) ? Math.max(0, Math.floor((new Date(end) - new Date(start)) / (1000*60*60*24)) + 1) : 0;
        if (document.getElementById('total-days')) document.getElementById('total-days').value = days;
        total = rate * days;
    } else if (type === 'fixed') {
        total = parseFloat(document.getElementById('fixed-amount')?.value) || 0;
    }

    const coachCount = document.querySelectorAll('#proposedCoachesTable tbody tr').length || 1;
    const grandTotal = total * coachCount;
    const totalEl    = document.getElementById('engagement-total');
    if (totalEl) totalEl.value = grandTotal > 0 ? 'KES ' + grandTotal.toLocaleString() : existingEngagementTotal;
}

document.addEventListener('DOMContentLoaded', function () {
    const tableBody      = document.querySelector('#proposedCoachesTable tbody');
    const addBtn         = document.getElementById('addCoachBtn');
    const numberInput    = document.querySelector('input[name="number_of_coaches"]');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput   = document.querySelector('input[name="end_date"]');
    const positionSelect = document.getElementById('positionTitleSelect');

    if (existingEngagementType) {
        document.getElementById('engagement-type').value = existingEngagementType;
        updateEngagementFields();
    }

    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function () {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) endDateInput.value = this.value;
        });
    }

    if (existingCoaches && existingCoaches.length > 0) {
        existingCoaches.forEach(coach => {
            tableBody.appendChild(createCoachRow(coach));
        });
    } else {
        tableBody.appendChild(createCoachRow(null));
    }
    updateCoachCount();
    reindexCoachRows();
    updateEngagementTotal();

    addBtn.addEventListener('click', function () {
        tableBody.appendChild(createCoachRow(null));
        updateCoachCount();
        updateEngagementTotal();
        reindexCoachRows();
    });

    numberInput.addEventListener('input', function () {
        let required = parseInt(numberInput.value) || 1;
        let current  = tableBody.querySelectorAll('tr').length;
        while (current < required) { tableBody.appendChild(createCoachRow(null)); current++; }
        while (current > required && current > 0) { tableBody.lastElementChild.remove(); current--; }
        updateCoachCount();
        updateEngagementTotal();
        reindexCoachRows();
    });

    if (positionSelect) {
        positionSelect.addEventListener('change', function () {
            document.querySelectorAll('.coach-select').forEach(function (select) {
                const currentValue = select.value;
                select.innerHTML = getUserOptions();
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === currentValue) { select.selectedIndex = i; break; }
                }
                select.dispatchEvent(new Event('change'));
            });
        });
    }
});
</script>
@endsection