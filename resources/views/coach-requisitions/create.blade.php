@extends('layouts.app')
@section('page-title', 'Requisition')
@section('content')
<div class="card">
    <div class="card-header"><h2 class="mb-0">Requisition Form</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('coach-requisitions.store') }}" id="requisitionForm">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Enter a descriptive title" required>
                </div>
            </div>

            <h5 class="mt-3 mb-3">Request Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Engagement Type</label>
                    <select class="form-select" name="engagement_type" id="engagement-type" required onchange="updateEngagementFields()">
                        <option value="">Select type...</option>
                        <!-- <option value="hourly">Hourly</option> -->
                        <option value="daily">Daily</option>
                        <!-- <option value="fixed">Fixed</option> -->
                    </select>
                </div>
                <div class="col-md-12" id="engagement-fields"></div>
                <input type="hidden" name="date_of_request" value="{{ date('Y-m-d') }}">
            </div>

            <h5 class="mt-3 mb-3">Position Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label>Position Title</label>
                    <select class="form-select" name="position_title" id="positionTitleSelect" required>
                        <option value="">-- Select Role --</option>
                        @php
                            $assignableRoles = [];
                            if (in_array($user->role->name, ['Admin', 'Manager'])) {
                                $assignableRoles = $roles;
                            } else {
                                $service = app(\Vanguard\Services\RoleHierarchyService::class);
                                $assignableNames = $service->getAllSubordinateRoles($user->role->name);
                                $assignableNames[] = $user->role->name;
                                $assignableRoles = $roles->filter(fn($r) => in_array($r->name, $assignableNames));
                            }
                        @endphp
                        @foreach($assignableRoles as $role)
                            <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                        @endforeach
                        @if(in_array($user->role->name, ['Admin', 'Manager']))
                            <option value="all">All</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Number of Positions Required</label>
                    <input type="number" class="form-control" name="number_of_coaches" min="1" required>
                </div>
                <div class="col-md-6">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="col-md-6">
                    <label>End Date</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
                <div class="col-md-6">
                    <label>Work Arrangement</label>
                    <select class="form-select" name="work_arrangement" required>
                        <option value="field">Field-Based</option>
                        <option value="office">Office-Based</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <input type="hidden" name="reporting_to"
                    value="{{ $supervisors->first() ? $supervisors->first()->first_name . ' ' . $supervisors->first()->last_name : '' }}">
            </div>

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
                <button type="submit" class="btn btn-primary">Submit Requisition</button>
            </div>

        </form>
    </div>
</div>

<script>
    // ════════════════════════════════════
    // USER DATA FROM PHP
    // ════════════════════════════════════
    const loggedInRole = @json($user->role ? $user->role->name : '');

    @php
        use Vanguard\Role;
        use Vanguard\User;
        use Vanguard\RegionalCoordinatorCounty;

        $roles = Role::all();
        $usersByRole = [];
        $currentUser = $user;
        $currentRole = $currentUser->role ? $currentUser->role->name : null;
        $currentSubcountyId = $currentUser->subcounty_id ?? null;

        // Regional_Coordinator is NOT tied to a single county via county_id.
        // Their counties live in the regional_coordinator_counties pivot table
        // (one coordinator can be assigned to many counties). Falling back to
        // $currentUser->county_id (as the previous version did) is null for
        // this role, which silently produced an empty user list for every
        // role bucket below. For every other role, county_id on the user
        // row itself is still the correct, single-value source.
        $currentCountyIds = $currentRole === 'Regional_Coordinator'
            ? RegionalCoordinatorCounty::where('user_id', $currentUser->id)->pluck('county_id')
            : collect([$currentUser->county_id ?? null])->filter()->values();

        $roleHierarchy = [
            'Admin' => ['Regional_Coordinator', 'County_Coordinator', 'Supervisor', 'Field_Officer', 'User', 'Finance', 'Manager', 'Partnerships_Growth_Manager'],
            'Manager' => ['Regional_Coordinator', 'County_Coordinator', 'Supervisor', 'Field_Officer', 'User', 'Finance', 'Partnerships_Growth_Manager'],
            'Regional_Coordinator' => ['County_Coordinator', 'Supervisor', 'Field_Officer', 'User'],
            'County_Coordinator' => ['Supervisor', 'Field_Officer', 'User'],
            'Supervisor' => ['Field_Officer', 'User'],
            'Field_Officer' => ['User'],
            'User' => [],
            'Finance' => [],
            'Partnerships_Growth_Manager' => []
        ];
        foreach ($roles as $role) {
            if ($currentRole === 'Admin') {
                $usersByRole[$role->name] = User::with('subcounty')->where('role_id', $role->id)->get()->map(function($u) {
                    return ['id' => $u->id, 'name' => $u->first_name . ' ' . $u->last_name, 'phone' => $u->phone ?? '', 'email' => $u->email ?? '', 'sub_county' => $u->subcounty->name ?? ''];
                })->values();
            } elseif ($currentRole === 'Manager') {
                $usersByRole[$role->name] = User::with('subcounty')->where('role_id', $role->id)->get()->map(function($u) {
                    return ['id' => $u->id, 'name' => $u->first_name . ' ' . $u->last_name, 'phone' => $u->phone ?? '', 'email' => $u->email ?? '', 'sub_county' => $u->subcounty->name ?? ''];
                })->values();
            } else {
                $subordinateRoles = $roleHierarchy[$currentRole] ?? [];
                if (in_array($role->name, $subordinateRoles)) {
                    $query = User::with('subcounty')->where('role_id', $role->id);
                    if ($currentRole === 'Regional_Coordinator' || $currentRole === 'County_Coordinator') {
                        $query->whereIn('county_id', $currentCountyIds);
                    } elseif ($currentRole === 'Supervisor') {
                        $query->where('subcounty_id', $currentSubcountyId);
                    }
                    $usersByRole[$role->name] = $query->get()->map(function($u) {
                        return ['id' => $u->id, 'name' => $u->first_name . ' ' . $u->last_name, 'phone' => $u->phone ?? '', 'email' => $u->email ?? '', 'sub_county' => $u->subcounty->name ?? ''];
                    })->values();
                } else {
                    $usersByRole[$role->name] = collect();
                }
            }
        }
        $usersByRole['all'] = in_array($currentRole, ['Admin', 'Manager'])
            ? User::with('subcounty')->get()->map(function($u) {
                return ['id' => $u->id, 'name' => $u->first_name . ' ' . $u->last_name, 'phone' => $u->phone ?? '', 'email' => $u->email ?? '', 'sub_county' => $u->subcounty->name ?? ''];
            })->values()
            : collect();
    @endphp

    const usersByRole = @json($usersByRole);

    let rowCounter = 0;

    // ════════════════════════════════════
    // HELPER: GET SELECTED ROLE
    // ════════════════════════════════════
    function getSelectedRole() {
        const positionSelect = document.getElementById('positionTitleSelect');
        let selectedRole = positionSelect ? positionSelect.value : '';
        if (!selectedRole || selectedRole === '') selectedRole = 'all';
        return selectedRole;
    }

    // ════════════════════════════════════
    // HELPER: BUILD COACH DROPDOWN OPTIONS
    // ════════════════════════════════════
    function getUserOptions(roleOverride) {
        let selectedRole = roleOverride || getSelectedRole();
        let users = [];
        if (!selectedRole) return '<option value="">-- Select Coach --</option>';
        if ((loggedInRole === 'Admin' || loggedInRole === 'Manager') && selectedRole === 'all') {
            users = usersByRole['all'] || [];
        } else {
            users = usersByRole[selectedRole] || [];
        }
        let options = '<option value="">-- Select Coach --</option>';
        if (!users || users.length === 0) {
            options += '<option value="" disabled>No users available for this role and location</option>';
        } else {
            users.forEach(u => {
                if (u.id && u.email) {
                    options += `<option value="${u.name}"
                        data-user-id="${u.id}"
                        data-phone="${u.phone}"
                        data-email="${u.email}"
                        data-subcounty="${u.sub_county}">
                        ${u.name}
                    </option>`;
                }
            });
        }
        return options;
    }

    // ════════════════════════════════════
    // CREATE A COACH TABLE ROW
    // ════════════════════════════════════
    function createCoachRow() {
        const rowIndex = rowCounter++;
        const tr = document.createElement('tr');
        let options = getUserOptions();

        tr.innerHTML = `
            <td>
                <select class="form-select coach-select" name="coach_full_name[]" required>
                    ${options}
                </select>
                <input type="hidden" class="coach-phone" name="coach_phone_number[]">
                <input type="hidden" class="coach-email" name="coach_email_address[]">
                <input type="hidden" class="coach-subcounty" name="coach_sub_county_assigned[]">
                <input type="hidden" class="coach-user-id" name="coach_user_id[]">
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="Increased workload">
                    <label class="form-check-label">Increased workload</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="New project activities">
                    <label class="form-check-label">New project activities</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="Staff shortage">
                    <label class="form-check-label">Staff shortage</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="Specialized skills requirement">
                    <label class="form-check-label">Specialized skills requirement</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="justification[${rowIndex}][]" value="Temporary assignment">
                    <label class="form-check-label">Temporary assignment</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input other-checkbox" type="checkbox" name="justification[${rowIndex}][]" value="Other">
                    <label class="form-check-label">Other (Specify)</label>
                </div>
                <div class="other-text-wrapper" style="display:none; margin-top:6px;">
                    <textarea class="form-control" name="justification_other[${rowIndex}]" rows="2" placeholder="Please specify..."></textarea>
                </div>
            </td>
            <td><textarea class="form-control" name="roles_responsibilities[]" rows="2" placeholder="Key duties..."></textarea></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-coach" title="Remove"><i class="fas fa-trash"></i></button></td>
        `;

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
            if (!this.checked) {
                otherWrapper.querySelector('textarea').value = '';
            }
        });

        tr.querySelector('.remove-coach').addEventListener('click', function () {
            tr.remove();
            updateCoachCount();
            updateEngagementTotal();
            reindexCoachRows();
        });

        return tr;
    }

    // ════════════════════════════════════
    // REINDEX COACH ROWS
    // ════════════════════════════════════
    function reindexCoachRows() {
        document.querySelectorAll('#proposedCoachesTable tbody tr').forEach(function(row, idx) {
            row.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                cb.name = `justification[${idx}][]`;
            });
            const otherTextarea = row.querySelector('.other-text-wrapper textarea');
            if (otherTextarea) {
                otherTextarea.name = `justification_other[${idx}]`;
            }
        });
    }

    // ════════════════════════════════════
    // UPDATE COACH COUNT INPUT
    // ════════════════════════════════════
    function updateCoachCount() {
        const count = document.querySelectorAll('#proposedCoachesTable tbody tr').length;
        const numberInput = document.querySelector('input[name="number_of_coaches"]');
        if (numberInput) numberInput.value = count > 0 ? count : 1;
    }

    // ════════════════════════════════════
    // ENGAGEMENT TERMS DYNAMIC FIELDS
    // ════════════════════════════════════
    function updateEngagementFields() {
        const type = document.getElementById('engagement-type').value;
        const fields = document.getElementById('engagement-fields');
        fields.innerHTML = '';
        if (!type) return;
        let html = '';
        if (type === 'hourly') {
            html = `<div class='row g-2'>
                <div class='col-md-4 mb-2'><label class='form-label'>Rate per Hour (KES)</label><input type='number' class='form-control' name='engagement_rate' id='rate-hour' min='0' required></div>
                <div class='col-md-4 mb-2'><label class='form-label'>Total Hours</label><input type='number' class='form-control' name='engagement_hours' id='total-hours' readonly></div>
                <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
            </div>`;
        } else if (type === 'daily') {
            html = `<div class='row g-2'>
                <div class='col-md-4 mb-2'><label class='form-label'>Rate per Day (KES)</label><input type='number' class='form-control' name='engagement_rate' id='rate-day' min='0' required></div>
                <div class='col-md-4 mb-2'><label class='form-label'>Total Days</label><input type='number' class='form-control' name='engagement_days' id='total-days' readonly></div>
                <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
            </div>`;
        } else if (type === 'fixed') {
            html = `<div class='row g-2'>
                <div class='col-md-4 mb-2'><label class='form-label'>Fixed Total Amount (KES)</label><input type='number' class='form-control' name='engagement_rate' id='fixed-amount' min='0' required></div>
                <div class='col-md-4 mb-2'></div>
                <div class='col-md-4 mb-2'><label class='form-label'>Total Amount</label><input type='text' class='form-control' id='engagement-total' name='engagement_total' readonly></div>
            </div>`;
        }
        fields.innerHTML = html;
        bindEngagementFieldListeners();
        updateEngagementTotal();
    }

    function bindEngagementFieldListeners() {
        // FIX: bind all three possible rate inputs so typing updates totals immediately
        ['rate-hour', 'rate-day', 'fixed-amount'].forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', updateEngagementTotal);
                input.addEventListener('change', updateEngagementTotal);
            }
        });
    }

    function updateEngagementTotal() {
        const type  = document.getElementById('engagement-type').value;
        const start = document.querySelector('input[name="start_date"]').value;
        const end   = document.querySelector('input[name="end_date"]').value;
        let total   = 0;

        if (type === 'hourly') {
            const rateEl = document.getElementById('rate-hour');
            const rate = rateEl ? (parseFloat(rateEl.value) || 0) : 0;
            let hours = 0;
            if (start && end) {
                hours = Math.max(0, Math.floor((new Date(end) - new Date(start)) / (1000 * 60 * 60)));
            }
            const totalHoursEl = document.getElementById('total-hours');
            if (totalHoursEl) totalHoursEl.value = hours;
            total = rate * hours;
        } else if (type === 'daily') {
            const rateEl = document.getElementById('rate-day');
            const rate = rateEl ? (parseFloat(rateEl.value) || 0) : 0;
            let days = 0;
            if (start && end) {
                days = Math.floor((new Date(end) - new Date(start)) / (1000 * 60 * 60 * 24)) + 1;
                days = days > 0 ? days : 0;
            }
            const totalDaysEl = document.getElementById('total-days');
            if (totalDaysEl) totalDaysEl.value = days;
            total = rate * days;
        } else if (type === 'fixed') {
            const fixedEl = document.getElementById('fixed-amount');
            total = fixedEl ? (parseFloat(fixedEl.value) || 0) : 0;
        }

        const coachCount = document.querySelectorAll('#proposedCoachesTable tbody tr').length || 1;
        const grandTotal = total * coachCount;
        const totalEl    = document.getElementById('engagement-total');
        if (totalEl) {
            totalEl.value = grandTotal > 0 ? 'KES ' + grandTotal.toLocaleString() : '';
        }
        const totalCostInput = document.querySelector('input[name="total_cost"]');
        if (totalCostInput) {
            totalCostInput.value = grandTotal > 0 ? grandTotal : '';
        }
    }

    // ════════════════════════════════════
    // DOM READY
    // ════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody      = document.querySelector('#proposedCoachesTable tbody');
        const addBtn         = document.getElementById('addCoachBtn');
        const numberInput    = document.querySelector('input[name="number_of_coaches"]');
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput   = document.querySelector('input[name="end_date"]');
        const positionSelect = document.getElementById('positionTitleSelect');

        // Prevent end date before start date, and recalculate totals on date change
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function () {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                updateEngagementTotal();
            });
            endDateInput.addEventListener('change', updateEngagementTotal);
        }

        // Add coach row button
        addBtn.addEventListener('click', function () {
            tableBody.appendChild(createCoachRow());
            updateCoachCount();
            updateEngagementTotal();
            reindexCoachRows();
        });

        // Sync rows when number input changes
        numberInput.addEventListener('input', function () {
            let required = parseInt(numberInput.value) || 1;
            let current  = tableBody.querySelectorAll('tr').length;
            while (current < required) { tableBody.appendChild(createCoachRow()); current++; }
            while (current > required && current > 0) { tableBody.lastElementChild.remove(); current--; }
            updateCoachCount();
            updateEngagementTotal();
            reindexCoachRows();
        });

        // Update coach dropdowns when position/role changes
        if (positionSelect) {
            positionSelect.addEventListener('change', function () {
                document.querySelectorAll('.coach-select').forEach(function (select) {
                    const currentValue = select.value;
                    select.innerHTML = getUserOptions();
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === currentValue) {
                            select.selectedIndex = i;
                            break;
                        }
                    }
                    select.dispatchEvent(new Event('change'));
                });
            });
        } // FIX: closing brace for if (positionSelect) — was missing, breaking all code below

        // Start with one empty row — now correctly OUTSIDE the if block
        tableBody.appendChild(createCoachRow());
        updateCoachCount();
        reindexCoachRows();
    });
</script>
@endsection