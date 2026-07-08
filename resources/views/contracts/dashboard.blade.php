@extends('layouts.app')

@section('page-title', __('Contracts Dashboard'))
@section('page-heading', __('Contracts Dashboard'))

@section('styles')
<style>
    .dashboard-card { border: 0; border-radius: 1rem; box-shadow: 0 0.125rem 0.75rem rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; }
    .dashboard-card:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15); }
    .dashboard-card .card-body { padding: 1.25rem; }
    .metric-icon { width: 48px; height: 48px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; }
    .metric-icon.primary { background: linear-gradient(135deg, #4e73df, #224abe); }
    .metric-icon.success { background: linear-gradient(135deg, #1cc88a, #13855c); }
    .metric-icon.warning { background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .metric-icon.danger { background: linear-gradient(135deg, #e74a3b, #be2617); }
    .metric-icon.info { background: linear-gradient(135deg, #36b9cc, #1c7c89); }
    .metric-icon.secondary { background: linear-gradient(135deg, #858796, #5a6268); }
    .table-responsive .table td, .table-responsive .table th { vertical-align: middle; }
    .urgency-critical { color: #e74a3b; }
    .urgency-warning { color: #f6c23e; }
    .urgency-normal { color: #1cc88a; }
    .compliance-bar { height: 24px; border-radius: 4px; }
    .kpi-value { font-size: 1.75rem; font-weight: 700; }
    .kpi-label { font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .chart-canvas-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
        max-width: 100%;
    }
    .chart-canvas-wrapper canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
    }
</style>
@endsection

@section('content')
    @include('partials.messages')

    @php
        $queryParams = http_build_query(request()->only(['search', 'county', 'project', 'role', 'status', 'signature_status', 'date_range', 'date_from', 'date_to']));
    @endphp

    <div class="card dashboard-card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1">Filter and focus the dashboard</h5>
                    <p class="text-muted mb-0">Review contract health, signatures and upcoming expiries in one place.</p>
                </div>
                <div class="btn-group mt-3 mt-md-0">
                    <a href="{{ route('contracts.dashboard.export', ['type' => 'csv']) }}{{ $queryParams ? '?' . $queryParams : '' }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                    <a href="{{ route('contracts.dashboard.export', ['type' => 'xlsx']) }}{{ $queryParams ? '?' . $queryParams : '' }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                    <a href="{{ route('contracts.dashboard.export', ['type' => 'pdf']) }}{{ $queryParams ? '?' . $queryParams : '' }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                </div>
            </div>

            <form action="{{ route('contracts.dashboard') }}" method="GET">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search contract title or user">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="county">County</label>
                        <select class="form-control" id="county" name="county">
                            <option value="">All counties</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" {{ request('county') == $county->id ? 'selected' : '' }}>{{ $county->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="project">Project</label>
                        <select class="form-control" id="project" name="project">
                            <option value="">All projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="">All roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Contract status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="signature_status">Signature status</label>
                        <select class="form-control" id="signature_status" name="signature_status">
                            <option value="">All signatures</option>
                            <option value="draft" {{ request('signature_status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ request('signature_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="accepted" {{ request('signature_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="declined" {{ request('signature_status') == 'declined' ? 'selected' : '' }}>Declined</option>
                            <option value="terminated" {{ request('signature_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="date_range">Date range</label>
                        <select class="form-control" id="date_range" name="date_range">
                            <option value="">Any time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This month</option>
                            <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This year</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="date_from">Start date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="date_to">End date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Go</button>
                    </div>
                </div>
                <div class="text-right">
                    @if(request()->hasAny(['search','county','project','role','status','signature_status','date_range','date_from','date_to']))
                        <a href="{{ route('contracts.dashboard') }}" class="btn btn-light">Clear filters</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Contracts</div>
                    <div class="kpi-value text-primary">{{ $summary['total'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon primary"><i class="fas fa-file-contract"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Contracts</div>
                    <div class="kpi-value text-success">{{ $summary['active'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon success"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Published</div>
                    <div class="kpi-value text-info">{{ $summary['published'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon info"><i class="fas fa-bullhorn"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-secondary h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Draft</div>
                    <div class="kpi-value text-secondary">{{ $summary['draft'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon secondary"><i class="fas fa-edit"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Expired</div>
                    <div class="kpi-value text-danger">{{ $summary['expired'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon danger"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expiring Soon</div>
                    <div class="kpi-value text-warning">{{ $summary['expiring_soon'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon warning"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Signatures</div>
                    <div class="kpi-value text-info">{{ $summary['pending_signatures'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon info"><i class="fas fa-pen-fancy"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Signed Contracts</div>
                    <div class="kpi-value text-success">{{ $summary['signed_signatures'] }}</div>
                    <div class="col-auto">
                        <div class="metric-icon success"><i class="fas fa-signature"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Declined Contracts</div>
                    <div class="kpi-value text-danger">{{ $summary['declined_contracts'] ?? 0 }}</div>
                    <div class="col-auto">
                        <div class="metric-icon danger"><i class="fas fa-thumbs-down"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card dashboard-card border-left-dark h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Terminated</div>
                    <div class="kpi-value text-dark">{{ $summary['terminated_contracts'] ?? 0 }}</div>
                    <div class="col-auto">
                        <div class="metric-icon" style="background: linear-gradient(135deg, #495057, #23282c);"><i class="fas fa-stop"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Contract status distribution</h6>
                    <div class="chart-canvas-wrapper">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Signature outcomes</h6>
                    <div class="chart-canvas-wrapper">
                        <canvas id="signatureChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-chart-line mr-2"></i>New contracts by month</h6>
                    <div class="chart-canvas-wrapper">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-tasks mr-2"></i>Lifecycle Summary</h6>
                    @foreach($lifecycleSummary as $item)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>{{ $item['label'] }}</span>
                                <span class="font-weight-bold">{{ $item['value'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $item['color'] }}" role="progressbar" style="width: {{ $summary['total'] ? min(100, round(($item['value'] / max(1, $summary['total'])) * 100)) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Signing Performance Section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-signature mr-2"></i>Contract Signing Performance</h6>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="kpi-value text-info">{{ $summary['total'] }}</div>
                                <div class="small text-muted">Total Issued</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="kpi-value text-success">{{ $summary['signed_signatures'] }}</div>
                                <div class="small text-muted">Total Signed</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="kpi-value text-warning">{{ $summary['pending_signatures'] }}</div>
                                <div class="small text-muted">Pending</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="kpi-value text-danger">{{ $summary['declined_contracts'] ?? 0 }}</div>
                                <div class="small text-muted">Declined</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Signing Completion Rate</span>
                            <span class="font-weight-bold">
                                @if($summary['total'] > 0)
                                    {{ round(($summary['signed_signatures'] / $summary['total']) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $summary['total'] > 0 ? round(($summary['signed_signatures'] / $summary['total']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-bell mr-2"></i>Upcoming Expiry Alerts</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-calendar-day text-danger mr-2"></i>Expiring Today</span>
                                <span class="badge badge-danger">{{ $upcomingExpiry['expiring_today'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-calendar-week text-warning mr-2"></i>This Week</span>
                                <span class="badge badge-warning">{{ $upcomingExpiry['expiring_this_week'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-calendar-alt text-info mr-2"></i>This Month</span>
                                <span class="badge badge-info">{{ $upcomingExpiry['expiring_this_month'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Contracts Table -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-clock mr-2"></i>Contracts Expiring in Next 30 Days</h6>
                    @if($expiringContracts->isEmpty())
                        <div class="alert alert-success mb-0"><i class="fas fa-check-circle mr-2"></i>No contracts are expiring in the next 30 days.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Contract Title</th>
                                        <th>End Date</th>
                                        <th class="text-right">Days Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringContracts as $contract)
                                        <tr>
                                            <td><strong>{{ $contract['title'] }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($contract['end_date'])->format(config('app.date_format')) }}</td>
                                            <td class="text-right">
                                                <span class="badge badge-{{ $contract['urgency'] === 'critical' ? 'danger' : ($contract['urgency'] === 'warning' ? 'warning' : 'success') }}">
                                                    {{ $contract['days_remaining'] }} days
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- County & Project Compliance -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-map-marked-alt mr-2"></i>County Compliance</h6>
                    @if($countyBreakdown->isEmpty())
                        <div class="alert alert-info mb-0">No county data available.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>County</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Signed</th>
                                        <th class="text-right">Compliance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($countyBreakdown as $county)
                                        @php
                                            $signed = $county['signed_contracts'] ?? 0;
                                            $total = $county['total_contracts'] ?? 0;
                                            $compliance = $county['compliance_percentage'] ?? 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $county['name'] }}</strong></td>
                                            <td class="text-right">{{ $total }}</td>
                                            <td class="text-right">{{ $signed }}</td>
                                            <td class="text-right">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <div class="compliance-bar bg-light" style="width: 40px;">
                                                        <div class="compliance-bar bg-{{ $compliance >= 80 ? 'success' : ($compliance >= 50 ? 'warning' : 'danger') }}" style="width: {{ min(100, $compliance) }}%"></div>
                                                    </div>
                                                    <span class="ml-2 small font-weight-bold">{{ $compliance }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-project-diagram mr-2"></i>Project Compliance</h6>
                    @if($projectBreakdown->isEmpty())
                        <div class="alert alert-info mb-0">No project data available.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Project</th>
                                        <th class="text-right">Issued</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projectBreakdown as $project)
                                        <tr>
                                            <td><strong>{{ $project['name'] }}</strong></td>
                                            <td class="text-right">{{ $project['count'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Breakdowns -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-history mr-2"></i>Recent Activity</h6>
                    @if($recentActivity->isEmpty())
                        <div class="alert alert-info mb-0">No recent activity to display.</div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity as $activity)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="font-weight-bold">{{ $activity['title'] }}</div>
                                            <div class="small text-muted">
                                                <i class="fas fa-clock mr-1"></i>{{ $activity['timestamp']->diffForHumans() }}
                                            </div>
                                        </div>
                                        <span class="badge badge-secondary ml-2">{{ $activity['badge'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-chart-bar mr-2"></i>Contract Distribution</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><strong>By County</strong></span>
                                <span class="badge badge-light">{{ $countyBreakdown->count() }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><strong>By Role</strong></span>
                                <span class="badge badge-light">{{ $roleBreakdown->count() }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span><strong>By Project</strong></span>
                                <span class="badge badge-light">{{ $projectBreakdown->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6 class="mt-3 mb-2">Top Roles</h6>
                    @forelse($roleBreakdown->take(5) as $role)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $role['name'] }}</span>
                            <span class="font-weight-bold text-primary">{{ $role['count'] }}</span>
                        </div>
                    @empty
                        <div class="text-muted small">No role data available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: @json($statusChart['labels']),
            datasets: [{
                label: 'Contracts',
                data: @json($statusChart['values']),
                backgroundColor: ['#6c757d', '#4e73df', '#e74a3b', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 8, right: 8, bottom: 8, left: 8 } },
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const signatureChart = new Chart(document.getElementById('signatureChart'), {
        type: 'doughnut',
        data: {
            labels: @json($signatureChart['labels']),
            datasets: [{
                data: @json($signatureChart['values']),
                backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b', '#36b9cc']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 8, right: 8, bottom: 8, left: 8 } },
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const trendChart = new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json(collect($monthlyTrend)->pluck('label')->all()),
            datasets: [{
                label: 'New contracts',
                data: @json(collect($monthlyTrend)->pluck('value')->all()),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.15)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 8, right: 8, bottom: 8, left: 8 } },
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection