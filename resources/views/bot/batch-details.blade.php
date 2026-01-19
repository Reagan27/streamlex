@extends('layouts.app')

@section('page-title', __('Bot Batch Details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-robot"></i> Batch Details: {{ $batch->batch_id }}
                        @if($isRatingBatch)
                            <span class="badge badge-warning ml-2">
                                <i class="fas fa-star"></i> Rating Batch
                            </span>
                        @endif
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-info" id="refresh-status">
                            <i class="fas fa-sync"></i> Refresh Status
                        </button>
                        <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Batches
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Batch Summary with Enhanced Status -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>{{ $batch->total_count }}</h3>
                                    <p>Total Messages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3 id="queued-count">{{ $batch->messages->where('status', 0)->count() }}</h3>
                                    <p>Queued</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3 id="sent-count">{{ $batch->sent_count }}</h3>
                                    <p>Sent by Bot</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3 id="delivered-count">{{ $batch->messages->where('status', 3)->count() }}</h3>
                                    <p>Delivered</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3 id="undelivered-count">{{ $batch->messages->where('status', 4)->count() }}</h3>
                                    <p>Undelivered</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h3 id="failed-count">{{ $batch->failed_count }}</h3>
                                    <p>Failed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Progress Bar -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Delivery Progress</h6>
                                    @php
                                        $totalCount = $batch->total_count ?: 1;
                                        $queuedPercent = ($batch->messages->where('status', 0)->count() / $totalCount) * 100;
                                        $sentPercent = ($batch->sent_count / $totalCount) * 100;
                                        $deliveredPercent = ($batch->messages->where('status', 3)->count() / $totalCount) * 100;
                                        $failedPercent = ($batch->failed_count / $totalCount) * 100;
                                    @endphp
                                    <div class="progress" style="height: 30px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ $queuedPercent }}%" 
                                             id="progress-queued">
                                            @if($queuedPercent > 5)
                                                {{ round($queuedPercent) }}% Queued
                                            @endif
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $sentPercent }}%" 
                                             id="progress-sent">
                                            @if($sentPercent > 5)
                                                {{ round($sentPercent) }}% Sent
                                            @endif
                                        </div>
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ $deliveredPercent }}%" 
                                             id="progress-delivered">
                                            @if($deliveredPercent > 5)
                                                {{ round($deliveredPercent) }}% Delivered
                                            @endif
                                        </div>
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: {{ $failedPercent }}%" 
                                             id="progress-failed">
                                            @if($failedPercent > 5)
                                                {{ round($failedPercent) }}% Failed
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($isRatingBatch)
                        <!-- Rating Statistics -->
                        <div class="card border-warning mb-4">
                            <div class="card-header bg-warning text-dark">
                                <i class="fas fa-chart-bar"></i> Rating Statistics
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <h4 class="text-success">{{ $ratedPhones->count() }}</h4>
                                            <p class="text-muted">Rated</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <h4 class="text-warning">{{ $unratedPhones->count() }}</h4>
                                            <p class="text-muted">Pending Response</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <h4 class="text-primary">
                                                {{ $ratings->where('thumb_rating', 'up')->count() }}
                                            </h4>
                                            <p class="text-muted"><i class="fas fa-thumbs-up"></i> Thumbs Up</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <h4 class="text-danger">
                                                {{ $ratings->where('thumb_rating', 'down')->count() }}
                                            </h4>
                                            <p class="text-muted"><i class="fas fa-thumbs-down"></i> Thumbs Down</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($ratings->whereNotNull('rating_score')->count() > 0)
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <strong>Average Numeric Rating:</strong> 
                                                {{ number_format($ratings->avg('rating_score'), 2) }} / 5
                                                <div class="rating-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= round($ratings->avg('rating_score')) ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tabs for Rated vs Unrated -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#all-messages">
                                    All Messages ({{ $batch->messages->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#rated-users">
                                    <i class="fas fa-check-circle text-success"></i> 
                                    Rated ({{ $ratedPhones->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#unrated-users">
                                    <i class="fas fa-clock text-warning"></i> 
                                    Pending ({{ $unratedPhones->count() }})
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div id="all-messages" class="tab-pane fade show active">
                    @endif

                    <!-- Batch Info -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Sent By:</strong> {{ $batch->user ? $batch->user->first_name . ' ' . $batch->user->last_name : 'N/A' }}<br>
                                <strong>Created:</strong> {{ $batch->created_at->format('Y-m-d H:i:s') }}<br>
                                <strong>Updated:</strong> {{ $batch->updated_at->format('Y-m-d H:i:s') }}<br>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $batch->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                                @if($isRatingBatch && isset($batch->filters['rating_config']))
                                    <br><strong>Rating Type:</strong> {{ ucfirst($batch->filters['rating_config']['rating_type'] ?? 'N/A') }}
                                    <br><strong>Allow Comments:</strong> {{ ($batch->filters['rating_config']['allow_comments'] ?? false) ? 'Yes' : 'No' }}
                                    <br><strong>Allow Skip:</strong> {{ ($batch->filters['rating_config']['allow_skip'] ?? false) ? 'Yes' : 'No' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Messages Table with Enhanced Status -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="messages-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Bot Status</th>
                                    @if($isRatingBatch)
                                        <th>Rating Status</th>
                                    @endif
                                    <th>Cost</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batch->messages as $index => $message)
                                <tr data-message-id="{{ $message->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $message->name ?? '-' }}</td>
                                    <td>{{ $message->phone }}</td>
                                    <td>
                                        <span data-toggle="tooltip" data-placement="top" title="{{ $message->message }}">
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
                                        <span class="badge badge-{{ $statusInfo['class'] }}">
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
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Rated
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $message->message_cost ? 'KES ' . number_format($message->message_cost, 2) : '-' }}</td>
                                    <td>{{ $message->date ? $message->date->format('Y-m-d H:i') : ($message->created_at ? $message->created_at->format('Y-m-d H:i') : '-') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $isRatingBatch ? 9 : 8 }}" class="text-center">
                                        <em>No messages found in this batch.</em>
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
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Phone</th>
                                                <th>Rating Type</th>
                                                <th>Score</th>
                                                <th>Thumb</th>
                                                <th>Comment</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ratings as $rating)
                                            <tr>
                                                <td>{{ $rating->phone_number }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucfirst($rating->rating_type) }}</span>
                                                </td>
                                                <td>
                                                    @if($rating->rating_score)
                                                        <div class="rating-stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $rating->rating_score ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rating->thumb_rating)
                                                        @if($rating->thumb_rating == 'up')
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-thumbs-up"></i> Up
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger">
                                                                <i class="fas fa-thumbs-down"></i> Down
                                                            </span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rating->comment)
                                                        <span data-toggle="tooltip" title="{{ $rating->comment }}">
                                                            {{ Str::limit($rating->comment, 30) }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <em>No ratings received yet.</em>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Unrated Users Tab -->
                            <div id="unrated-users" class="tab-pane fade">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i> 
                                    These recipients have not yet responded with a rating.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Phone</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Sent At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $unratedMessages = $batch->messages->whereIn('phone', $unratedPhones);
                                            @endphp
                                            @forelse($unratedMessages as $index => $message)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $message->phone }}</td>
                                                <td>{{ $message->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $message->status == 1 ? 'success' : 'warning' }}">
                                                        {{ $message->status_description }}
                                                    </span>
                                                </td>
                                                <td>{{ $message->sent_at ? $message->sent_at->format('Y-m-d H:i') : '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <em>All recipients have provided ratings!</em>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($batch->messages->count() > 0)
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Cost:</strong> 
                                    KES {{ number_format($batch->messages->sum('message_cost'), 2) }}
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>Success Rate:</strong> 
                                    {{ $batch->total_count > 0 ? number_format(($batch->sent_count / $batch->total_count) * 100, 2) : 0 }}%
                                    @if($isRatingBatch && $ratedPhones->count() > 0)
                                        <br>
                                        <strong>Response Rate:</strong> 
                                        {{ number_format(($ratedPhones->count() / $batch->total_count) * 100, 2) }}%
                                    @endif
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
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh status updates
    let refreshInterval;
    
    function refreshStatus() {
        $.ajax({
            url: window.location.href,
            method: 'GET',
            success: function(html) {
                // Extract updated counts from response
                const $html = $(html);
                
                // Update status counts
                $('#queued-count').text($html.find('#queued-count').text());
                $('#sent-count').text($html.find('#sent-count').text());
                $('#delivered-count').text($html.find('#delivered-count').text());
                $('#undelivered-count').text($html.find('#undelivered-count').text());
                $('#failed-count').text($html.find('#failed-count').text());
                
                // Update progress bars
                $('#progress-queued').css('width', $html.find('#progress-queued').css('width'));
                $('#progress-sent').css('width', $html.find('#progress-sent').css('width'));
                $('#progress-delivered').css('width', $html.find('#progress-delivered').css('width'));
                $('#progress-failed').css('width', $html.find('#progress-failed').css('width'));
                
                // Update individual message statuses
                $html.find('#messages-table tbody tr').each(function() {
                    const messageId = $(this).data('message-id');
                    const newStatus = $(this).find('.status-cell').html();
                    const newBotStatus = $(this).find('.bot-status-cell').html();
                    
                    $(`tr[data-message-id="${messageId}"] .status-cell`).html(newStatus);
                    $(`tr[data-message-id="${messageId}"] .bot-status-cell`).html(newBotStatus);
                });
                
                console.log('✅ Status updated at ' + new Date().toLocaleTimeString());
            },
            error: function() {
                console.error('Failed to refresh status');
            }
        });
    }
    
    // Manual refresh button
    $('#refresh-status').on('click', function() {
        $(this).find('i').addClass('fa-spin');
        refreshStatus();
        setTimeout(() => {
            $(this).find('i').removeClass('fa-spin');
        }, 1000);
    });
    
    // Auto-refresh every 10 seconds if batch is still processing
    @if($batch->status == 'processing')
        refreshInterval = setInterval(refreshStatus, 10000);
        
        // Show notification that auto-refresh is active
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Auto-refresh enabled',
            text: 'Status updates every 10 seconds',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
    
    // Clean up interval on page unload
    $(window).on('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.stat-card {
    text-align: center;
    padding: 15px;
    border-radius: 5px;
    background: #f8f9fa;
}
.stat-card h4 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}
.rating-stars {
    display: inline-block;
}
.status-cell .badge {
    min-width: 100px;
}
.bot-status-cell small {
    cursor: help;
}
.progress {
    margin-bottom: 10px;
}
.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endpush
@endsection