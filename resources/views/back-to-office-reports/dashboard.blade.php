@extends('layouts.app')

@section('page-title', __('Back to Office Reports Dashboard'))
@section('page-heading', __('Back to Office Reports Dashboard'))

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
    .kpi-value { font-size: 1.75rem; font-weight: 700; }
    .dashboard-chart-wrapper {
        position: relative;
        width: 100%;
        height: 260px;
        max-width: 100%;
    }
    .dashboard-chart-wrapper canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.messages')

    <div class="row mb-4">
        @foreach([
            ['label' => 'Total Reports', 'value' => $stats['total'], 'icon' => 'fas fa-file-alt', 'class' => 'primary'],
            ['label' => 'Draft Reports', 'value' => $stats['draft'], 'icon' => 'fas fa-edit', 'class' => 'secondary'],
            ['label' => 'Submitted Reports', 'value' => $stats['submitted'], 'icon' => 'fas fa-paper-plane', 'class' => 'info'],
            ['label' => 'Approved Reports', 'value' => $stats['approved'], 'icon' => 'fas fa-check-circle', 'class' => 'success'],
            ['label' => 'Pending Approvals', 'value' => $stats['pending_approvals'], 'icon' => 'fas fa-clock', 'class' => 'warning'],
            ['label' => 'Overdue Reports', 'value' => $stats['overdue'], 'icon' => 'fas fa-exclamation-triangle', 'class' => 'danger'],
        ] as $card)
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card dashboard-card border-left-{{ $card['class'] }} h-100 py-2">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-{{ $card['class'] }} text-uppercase mb-1">{{ $card['label'] }}</div>
                            <div class="kpi-value text-{{ $card['class'] }}">{{ $card['value'] }}</div>
                        </div>
                        <div class="metric-icon {{ $card['class'] }}">
                            <i class="{{ $card['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Overview</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusLabels = collect($statusBreakdown)->keys()->map(fn ($value) => ucfirst($value))->all();
                        $statusValues = collect($statusBreakdown)->values()->all();
                        $categoryLabels = collect($categoryBreakdown)->keys()->map(fn ($value) => ucfirst($value))->all();
                        $categoryValues = collect($categoryBreakdown)->values()->all();
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Status distribution</h6>
                            @if(empty($statusValues))
                                <div class="border rounded p-3 bg-light-subtle">No report status data yet.</div>
                            @else
                                <div class="border rounded p-3 bg-light-subtle dashboard-chart-wrapper">
                                    <canvas id="backOfficeStatusChart"></canvas>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted">Reports by project</h6>
                            @if(empty($categoryValues))
                                <div class="border rounded p-3 bg-light-subtle">No project data yet.</div>
                            @else
                                <div class="border rounded p-3 bg-light-subtle dashboard-chart-wrapper">
                                    <canvas id="backOfficeCategoryChart"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted">Reports by Project</h6>
                        @foreach($categoryBreakdown as $project => $count)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>{{ Str::limit($project, 30) }}</span>
                                    <span>{{ $count }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-dark" style="width: {{ $stats['total'] ? ($count / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('back-to-office-reports.create') }}" class="btn btn-dark rounded-pill"><i class="fas fa-plus me-2"></i>Create New Report</a>
                    <a href="{{ route('back-to-office-reports.index', ['status' => 'draft']) }}" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-edit me-2"></i>View Draft Reports</a>
                    <a href="{{ route('back-to-office-reports.index', ['status' => 'submitted']) }}" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-paper-plane me-2"></i>View Submitted Reports</a>
                    <a href="{{ route('back-to-office-reports.index', ['status' => 'submitted']) }}" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-clock me-2"></i>View Pending Approvals</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Counties submitting</h5>
                </div>
                <div class="card-body">
                    @forelse($countyActivity as $county => $count)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">{{ $county }}</div>
                                <div class="small text-muted">{{ $count }} report{{ $count !== 1 ? 's' : '' }}</div>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No county submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Active submitters</h5>
                </div>
                <div class="card-body">
                    @forelse($topSubmitters as $user => $count)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">{{ $user }}</div>
                                <div class="small text-muted">{{ $count }} report{{ $count !== 1 ? 's' : '' }}</div>
                            </div>
                            <span class="badge rounded-pill bg-success-subtle text-success">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No contributors yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Needs attention</h5>
                </div>
                <div class="card-body">
                    @forelse($lowSubmitters as $user => $count)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">{{ $user }}</div>
                                <div class="small text-muted">{{ $count }} report{{ $count !== 1 ? 's' : '' }}</div>
                            </div>
                            <span class="badge rounded-pill bg-warning-subtle text-warning">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Great job — no follow-up needed.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusCtx = document.getElementById('backOfficeStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        label: 'Reports',
                        data: @json($statusValues),
                        backgroundColor: ['#4e73df', '#858796', '#36b9cc', '#1cc88a', '#f6c23e', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        const categoryCtx = document.getElementById('backOfficeCategoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        data: @json($categoryValues),
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
@endsection
