@extends('layouts.app')

@section('page-title', __('Bot Ratings'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star"></i> Bot Ratings & Feedback
                    </h5>
                    <a href="{{ route('bot.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Batches
                    </a>
                </div>
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['total_ratings'] }}</h3>
                                    <p>Total Ratings</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3>{{ number_format($stats['average_score'], 2) }}</h3>
                                    <p>Average Score</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['thumbs_up'] }}</h3>
                                    <p>Thumbs Up</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['thumbs_down'] }}</h3>
                                    <p>Thumbs Down</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ratings Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Session ID</th>
                                    <th>User</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Score</th>
                                    <th>Thumb Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ratings as $rating)
                                <tr>
                                    <td>{{ $rating->session_id }}</td>
                                    <td>{{ $rating->user ? $rating->user->first_name . ' ' . $rating->user->last_name : 'Guest' }}</td>
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
                                            <span data-toggle="tooltip" data-placement="top" title="{{ $rating->comment }}">
                                                {{ Str::limit($rating->comment, 50) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <em>No ratings found.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $ratings->links() }}
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
});
</script>
@endpush
@endsection