@extends('layouts.app')

@section('page-title', __('Bot Batch Details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-robot"></i> Batch Details: <span class="badge badge-light text-primary">{{ $batch->batch_id }}</span>
                        @if($isRatingBatch)
                            <span class="badge badge-warning ml-2 pulse-animation">
                                <i class="fas fa-star"></i> Rating Batch
                            </span>
                        @endif
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-light" id="refresh-status">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                        <a href="{{ route('bot.index') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Batch Info -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-outline-info shadow-sm hover-lift" type="button" data-toggle="collapse" data-target="#batchInfoCollapse">
                                <i class="fas fa-info-circle"></i> View Batch Details
                            </button>
                            <div class="collapse" id="batchInfoCollapse">
                                <div class="alert alert-info mt-3 shadow-sm border-left-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><i class="fas fa-user text-primary"></i> <strong>Sent By:</strong> {{ $batch->user ? $batch->user->first_name . ' ' . $batch->user->last_name : 'N/A' }}</p>
                                            <p class="mb-2"><i class="fas fa-calendar-plus text-success"></i> <strong>Created:</strong> {{ $batch->created_at->format('Y-m-d H:i:s') }}</p>
                                            <p class="mb-0"><i class="fas fa-calendar-check text-info"></i> <strong>Updated:</strong> {{ $batch->updated_at->format('Y-m-d H:i:s') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <i class="fas fa-flag text-warning"></i> <strong>Status:</strong> 
                                                <span class="badge badge-{{ $batch->status == 'completed' ? 'success' : 'warning' }} badge-pill px-3">
                                                    {{ ucfirst($batch->status) }}
                                                </span>
                                            </p>
                                            @if($isRatingBatch && isset($batch->filters['rating_config']))
                                                <p class="mb-2"><i class="fas fa-star text-warning"></i> <strong>Rating Type:</strong> {{ ucfirst($batch->filters['rating_config']['rating_type'] ?? 'N/A') }}</p>
                                                <p class="mb-2"><i class="fas fa-comment text-primary"></i> <strong>Allow Comments:</strong> {{ ($batch->filters['rating_config']['allow_comment'] ?? false) ? 'Yes' : 'No' }}</p>
                                                <p class="mb-0"><i class="fas fa-forward text-secondary"></i> <strong>Allow Skip:</strong> {{ ($batch->filters['rating_config']['allow_skip'] ?? false) ? 'Yes' : 'No' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        @if($isRatingBatch)
                            <!-- Scale Rating Bar Chart -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card border-0 shadow-sm hover-lift">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-star"></i> Scale Rating Distribution (1-5)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="scaleChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbs Up/Down Pie Chart -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm hover-lift">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-thumbs-up"></i> Thumbs Rating Distribution</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="thumbsChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Delivery Progress Chart -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card border-0 shadow-sm hover-lift">
                                    <div class="card-header bg-gradient-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-chart-line"></i> Delivery Progress</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="deliveryProgressChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Empty space or add another chart if needed -->
                            </div>
                        @endif
                    </div>

                    <!-- Toggle Navigation -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <ul class="nav nav-pills nav-fill custom-nav-pills shadow-sm" id="stats-toggle" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="delivery-tab" data-toggle="tab" href="#delivery-stats" role="tab">
                                        <i class="fas fa-paper-plane"></i> Delivery Status Overview
                                    </a>
                                </li>
                                @if($isRatingBatch)
                                    <li class="nav-item">
                                        <a class="nav-link" id="rating-tab" data-toggle="tab" href="#rating-stats" role="tab">
                                            <i class="fas fa-star"></i> Rating Insights
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content mb-4">
                        <!-- Delivery Status Statistics -->
                        <div class="tab-pane fade show active" id="delivery-stats" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-2 col-6 mb-3 mb-md-0">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-clock fa-2x text-secondary"></i>
                                                </div>
                                                <h3 class="text-secondary mb-1">{{ $batch->messages->where('status', 0)->count() }}</h3>
                                                <p class="text-muted mb-0 small">Queued</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 mb-3 mb-md-0">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-paper-plane fa-2x text-success"></i>
                                                </div>
                                                <h3 class="text-success mb-1">{{ $batch->sent_count }}</h3>
                                                <p class="text-muted mb-0 small">Sent by Bot</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 mb-3 mb-md-0">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                </div>
                                                <h3 class="text-primary mb-1">{{ $batch->messages->where('status', 3)->count() }}</h3>
                                                <p class="text-muted mb-0 small">Delivered</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 mb-3 mb-md-0">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
                                                </div>
                                                <h3 class="text-warning mb-1">{{ $batch->messages->where('status', 4)->count() }}</h3>
                                                <p class="text-muted mb-0 small">Undelivered</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6 mb-3 mb-md-0">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                                </div>
                                                <h3 class="text-danger mb-1">{{ $batch->failed_count }}</h3>
                                                <p class="text-muted mb-0 small">Failed</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-list fa-2x text-info"></i>
                                                </div>
                                                <h3 class="text-info mb-1">{{ $batch->total_count }}</h3>
                                                <p class="text-muted mb-0 small">Total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($isRatingBatch)
                            <!-- Rating Insights -->
                            <div class="tab-pane fade" id="rating-stats" role="tabpanel">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                                <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                    <div class="stat-icon mb-2">
                                                        <i class="fas fa-comment-dots fa-2x text-success"></i>
                                                    </div>
                                                    <h3 class="text-success mb-1">{{ $ratedPhones->count() }}</h3>
                                                    <p class="text-muted mb-0 small">Total Responses</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                                <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                    <div class="stat-icon mb-2">
                                                        <i class="fas fa-percentage fa-2x text-info"></i>
                                                    </div>
                                                    <h3 class="text-info mb-1">
                                                        {{ $batch->total_count > 0 ? number_format(($ratedPhones->count() / $batch->total_count) * 100, 1) : 0 }}%
                                                    </h3>
                                                    <p class="text-muted mb-0 small">Response Rate</p>
                                                </div>
                                            </div>
                                            @if($ratings->whereNotNull('rating_score')->count() > 0)
                                                <div class="col-md-3 col-6 mb-3 mb-md-0">
                                                    <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                        <div class="stat-icon mb-2">
                                                            <i class="fas fa-star fa-2x text-warning"></i>
                                                        </div>
                                                        <h3 class="text-warning mb-1">{{ number_format($ratings->avg('rating_score'), 2) }}</h3>
                                                        <p class="text-muted mb-0 small">Average Score</p>
                                                        <div class="rating-stars mt-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= round($ratings->avg('rating_score')) ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-3 col-6">
                                                <div class="stat-card text-center p-3 bg-light rounded hover-scale">
                                                    <div class="stat-icon mb-2">
                                                        <i class="fas fa-thumbs-up fa-2x text-success"></i>
                                                    </div>
                                                    <h3 class="mb-1">
                                                        <span class="text-success">{{ $ratings->where('thumb_rating', 'up')->count() }}</span>
                                                        <span class="text-muted">/</span>
                                                        <span class="text-danger">{{ $ratings->where('thumb_rating', 'down')->count() }}</span>
                                                    </h3>
                                                    <p class="text-muted mb-0 small">
                                                        <i class="fas fa-thumbs-up text-success"></i> / 
                                                        <i class="fas fa-thumbs-down text-danger"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($isRatingBatch)
                        <!-- Tabs for Rated vs Unrated -->
                        <ul class="nav nav-tabs custom-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#all-messages">
                                    <i class="fas fa-list"></i> All Messages <span class="badge badge-primary ml-1">{{ $batch->messages->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#rated-users">
                                    <i class="fas fa-check-circle text-success"></i> 
                                    Rated <span class="badge badge-success ml-1">{{ $ratedPhones->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#unrated-users">
                                    <i class="fas fa-clock text-warning"></i> 
                                    Pending <span class="badge badge-warning ml-1">{{ $unratedPhones->count() }}</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div id="all-messages" class="tab-pane fade show active">
                    @endif

                    <!-- Messages Table -->
                    <div class="table-responsive">
                        <table class="table table-hover custom-table" id="messages-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th><i class="fas fa-user"></i> Name</th>
                                    <th><i class="fas fa-phone"></i> Phone</th>
                                    <th><i class="fas fa-comment"></i> Message</th>
                                    <th><i class="fas fa-flag"></i> Status</th>
                                    <th><i class="fas fa-robot"></i> Bot Status</th>
                                    @if($isRatingBatch)
                                        <th><i class="fas fa-star"></i> Rating Status</th>
                                    @endif
                                    <th><i class="fas fa-calendar"></i> Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batch->messages as $index => $message)
                                <tr data-message-id="{{ $message->id }}" class="table-row-hover">
                                    <td><span class="badge badge-light">{{ $index + 1 }}</span></td>
                                    <td>{{ $message->name ?? '-' }}</td>
                                    <td><span class="text-muted">{{ $message->phone }}</span></td>
                                    <td>
                                        <span data-toggle="tooltip" data-placement="top" title="{{ $message->message }}" class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ Str::limit($message->message, 50) }}
                                        </span>
                                    </td>
                                    <td class="status-cell">
                                        @php
                                            $statusBadgeMap = [
                                                0 => ['class' => 'secondary', 'text' => 'Queued', 'icon' => 'clock'],
                                                1 => ['class' => 'success', 'text' => 'Sent', 'icon' => 'paper-plane'],
                                                2 => ['class' => 'danger', 'text' => 'Failed', 'icon' => 'times-circle'],
                                                3 => ['class' => 'primary', 'text' => 'Delivered', 'icon' => 'check-circle'],
                                                4 => ['class' => 'warning', 'text' => 'Undelivered', 'icon' => 'exclamation-circle'],
                                            ];
                                            $statusInfo = $statusBadgeMap[$message->status] ?? ['class' => 'secondary', 'text' => 'Unknown', 'icon' => 'question'];
                                        @endphp
                                        <span class="badge badge-{{ $statusInfo['class'] }} badge-pill">
                                            <i class="fas fa-{{ $statusInfo['icon'] }}"></i> {{ $statusInfo['text'] }}
                                        </span>
                                    </td>
                                    <td class="bot-status-cell">
                                        @if($message->status_message)
                                            <small class="text-muted" title="{{ $message->response }}">
                                                {{ $message->status_message }}
                                            </small>
                                        @else
                                            <small class="text-muted">Pending</small>
                                        @endif
                                    </td>
                                    @if($isRatingBatch)
                                        <td>
                                            @if($ratedPhones->contains($message->phone))
                                                <span class="badge badge-success badge-pill">
                                                    <i class="fas fa-check-circle"></i> Rated
                                                </span>
                                            @else
                                                <span class="badge badge-warning badge-pill">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                    <td><small class="text-muted">{{ $message->date ? $message->date->format('Y-m-d H:i') : ($message->created_at ? $message->created_at->format('Y-m-d H:i') : '-') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $isRatingBatch ? 9 : 8 }}" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted"><em>No messages found in this batch.</em></p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($isRatingBatch)
                            </div>

                            <!-- Rated Users Tab -->
                            <div id="rated-users" class="tab-pane fade">
                                <div class="table-responsive">
                                    <table class="table table-hover custom-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th><i class="fas fa-phone"></i> Phone</th>
                                                <th><i class="fas fa-tag"></i> Rating Type</th>
                                                <th><i class="fas fa-star"></i> Score</th>
                                                <th><i class="fas fa-thumbs-up"></i> Thumb</th>
                                                <th><i class="fas fa-comment"></i> Comment</th>
                                                <th><i class="fas fa-calendar"></i> Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ratings as $rating)
                                            <tr class="table-row-hover">
                                                <td><span class="text-muted">{{ $rating->phone_number }}</span></td>
                                                <td>
                                                    <span class="badge badge-info badge-pill">{{ ucfirst($rating->rating_type) }}</span>
                                                </td>
                                                <td>
                                                    @if($rating->rating_score)
                                                        <div class="rating-stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $rating->rating_score ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rating->thumb_rating)
                                                        @if($rating->thumb_rating == 'up')
                                                            <span class="badge badge-success badge-pill">
                                                                <i class="fas fa-thumbs-up"></i> Up
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger badge-pill">
                                                                <i class="fas fa-thumbs-down"></i> Down
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rating->comment)
                                                        <span data-toggle="tooltip" title="{{ $rating->comment }}" class="text-truncate d-inline-block" style="max-width: 200px;">
                                                            {{ Str::limit($rating->comment, 30) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td><small class="text-muted">{{ $rating->created_at->format('Y-m-d H:i') }}</small></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted"><em>No ratings received yet.</em></p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Unrated Users Tab -->
                            <div id="unrated-users" class="tab-pane fade">
                                <div class="alert alert-warning shadow-sm border-left-warning">
                                    <i class="fas fa-info-circle"></i> 
                                    These recipients have not yet responded with a rating.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover custom-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th><i class="fas fa-phone"></i> Phone</th>
                                                <th><i class="fas fa-user"></i> Name</th>
                                                <th><i class="fas fa-flag"></i> Status</th>
                                                <th><i class="fas fa-calendar"></i> Sent At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $unratedMessages = $batch->messages->whereIn('phone', $unratedPhones);
                                            @endphp
                                            @forelse($unratedMessages as $index => $message)
                                            <tr class="table-row-hover">
                                                <td><span class="badge badge-light">{{ $index + 1 }}</span></td>
                                                <td><span class="text-muted">{{ $message->phone }}</span></td>
                                                <td>{{ $message->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $message->status == 1 ? 'success' : 'warning' }} badge-pill">
                                                        {{ $message->status_description }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $message->sent_at ? $message->sent_at->format('Y-m-d H:i') : '-' }}</small></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                                    <p class="text-success"><em>All recipients have provided ratings!</em></p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
  
    $('[data-toggle="tooltip"]').tooltip();
    
    
    const queuedCount = {{ $batch->messages->where('status', 0)->count() }};
    const sentCount = {{ $batch->sent_count }};
    const deliveredCount = {{ $batch->messages->where('status', 3)->count() }};
    const undeliveredCount = {{ $batch->messages->where('status', 4)->count() }};
    const failedCount = {{ $batch->failed_count }};
    
    @if($isRatingBatch)
       
        const scale1Count = {{ $ratings->where('rating_score', 1)->count() }};
        const scale2Count = {{ $ratings->where('rating_score', 2)->count() }};
        const scale3Count = {{ $ratings->where('rating_score', 3)->count() }};
        const scale4Count = {{ $ratings->where('rating_score', 4)->count() }};
        const scale5Count = {{ $ratings->where('rating_score', 5)->count() }};
        
        const scaleCtx = document.getElementById('scaleChart').getContext('2d');
        new Chart(scaleCtx, {
            type: 'bar',
            data: {
                labels: ['⭐', '⭐⭐', '⭐⭐⭐', '⭐⭐⭐⭐', '⭐⭐⭐⭐⭐'],
                datasets: [{
                    label: 'Ratings',
                    data: [scale1Count, scale2Count, scale3Count, scale4Count, scale5Count],
                    backgroundColor: [
                        '#dc3545',
                        '#fd7e14',
                        '#ffc107',
                        '#28a745',
                        '#20c997'
                    ],
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                const total = scale1Count + scale2Count + scale3Count + scale4Count + scale5Count;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `Count: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

      
        const thumbsUpCount = {{ $ratings->where('thumb_rating', 'up')->count() }};
        const thumbsDownCount = {{ $ratings->where('thumb_rating', 'down')->count() }};
        
        const thumbsCtx = document.getElementById('thumbsChart').getContext('2d');
        new Chart(thumbsCtx, {
            type: 'doughnut',
            data: {
                labels: ['👍 Thumbs Up', '👎 Thumbs Down'],
                datasets: [{
                    data: [thumbsUpCount, thumbsDownCount],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = thumbsUpCount + thumbsDownCount;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    @else
       
        const progressCtx = document.getElementById('deliveryProgressChart').getContext('2d');
        new Chart(progressCtx, {
            type: 'bar',
            data: {
                labels: ['Queued', 'Sent', 'Delivered', 'Failed'],
                datasets: [{
                    label: 'Messages',
                    data: [queuedCount, sentCount, deliveredCount, failedCount],
                    backgroundColor: [
                        '#6c757d',
                        '#28a745',
                        '#007bff',
                        '#dc3545'
                    ],
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    @endif
    
  
    let refreshInterval;
    
    function refreshStatus() {
        $.ajax({
            url: window.location.href,
            method: 'GET',
            success: function(html) {
                const $html = $(html);
                
               
                $html.find('#messages-table tbody tr').each(function() {
                    const messageId = $(this).data('message-id');
                    const newStatus = $(this).find('.status-cell').html();
                    const newBotStatus = $(this).find('.bot-status-cell').html();
                    
                    const $row = $(`tr[data-message-id="${messageId}"]`);
                    $row.find('.status-cell').fadeOut(200, function() {
                        $(this).html(newStatus).fadeIn(200);
                    });
                    $row.find('.bot-status-cell').fadeOut(200, function() {
                        $(this).html(newBotStatus).fadeIn(200);
                    });
                });
                
                console.log('✅ Status updated at ' + new Date().toLocaleTimeString());
            },
            error: function() {
                console.error('❌ Failed to refresh status');
            }
        });
    }
    
   
    $('#refresh-status').on('click', function() {
        const $btn = $(this);
        $btn.find('i').addClass('fa-spin');
        $btn.prop('disabled', true);
        
        setTimeout(function() {
            location.reload();
        }, 300);
    });
    
   
    @if($batch->status == 'processing')
        refreshInterval = setInterval(refreshStatus, 10000);
    @endif
    
   
    $(window).on('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
    
   
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('html, body').animate({
            scrollTop: $($(e.target).attr('href')).offset().top - 100
        }, 500);
    });
});
</script>
@endpush

@push('styles')
<style>

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: 2px solid rgba(0,0,0,0.05);
}

.shadow-lg {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}


.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}


.stat-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.stat-icon {
    transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}


.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.hover-scale {
    transition: transform 0.2s ease;
}

.hover-scale:hover {
    transform: scale(1.02);
}


.custom-table {
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    color: #495057;
}

.custom-table tbody tr {
    transition: all 0.3s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.custom-table tbody td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-top: 1px solid #e9ecef;
}


.badge-pill {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.85rem;
}


.rating-stars {
    display: inline-block;
    font-size: 1.1rem;
}

.rating-stars i {
    margin: 0 1px;
}


.custom-tabs {
    border-bottom: 2px solid #e9ecef;
}

.custom-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.custom-tabs .nav-link:hover {
    color: #007bff;
    background: #f8f9fa;
}

.custom-tabs .nav-link.active {
    color: #007bff;
    border-bottom: 3px solid #007bff;
    background: transparent;
}


.custom-nav-pills {
    background: white;
    border-radius: 10px;
    padding: 5px;
}

.custom-nav-pills .nav-link {
    border-radius: 8px;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.75rem 1.5rem;
}

.custom-nav-pills .nav-link:hover {
    background: #e9ecef;
    color: #495057;
}

.custom-nav-pills .nav-link.active {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}


.border-left-info {
    border-left: 4px solid #17a2b8;
}

.border-left-warning {
    border-left: 4px solid #ffc107;
}


.btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-light:hover {
    background: white;
    color: #007bff;
}

.btn-outline-light:hover {
    background: white;
    color: #007bff;
}


@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.pulse-animation {
    animation: pulse 2s infinite;
}


.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .custom-nav-pills .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .custom-table {
        font-size: 0.85rem;
    }
}


* {
    transition: all 0.3s ease;
}


.tooltip-inner {
    max-width: 300px;
    padding: 8px 12px;
    background-color: rgba(0, 0, 0, 0.9);
    border-radius: 6px;
}
</style>
@endpush
@endsection