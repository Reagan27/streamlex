@extends('layouts.app')

@section('page-title', __('Bot Messages'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-robot"></i> Bot Message Batches
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('bot.ratings') }}" class="btn btn-info mr-2">
                            <i class="fas fa-star"></i> View Ratings
                        </a>
                        <a href="{{ route('bot.issues') }}" class="btn btn-warning mr-2">
                            <i class="fas fa-exclamation-circle"></i> View Issues
                        </a>
                        <a href="{{ route('bot.compose') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Send New Message
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['total_batches'] }}</h3>
                                    <p>Total Batches</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['total_messages'] }}</h3>
                                    <p>Total Messages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['sent_today'] }}</h3>
                                    <p>Sent Today</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3>{{ $stats['pending'] }}</h3>
                                    <p>Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Batches Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Batch ID</th>
                                    <th>Sent By</th>
                                    <th>Total</th>
                                    <th>Sent</th>
                                    <th>Failed</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_id }}</td>
                                    <td>{{ $batch->user ? $batch->user->first_name : 'N/A' }}</td>
                                    <td>{{ $batch->total_count }}</td>
                                    <td><span class="badge badge-success">{{ $batch->sent_count }}</span></td>
                                    <td><span class="badge badge-danger">{{ $batch->failed_count }}</span></td>
                                    <td>
                                        @if($batch->status == 'processing')
                                            <span class="badge badge-warning">Processing</span>
                                        @elseif($batch->status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $batch->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('bot.batch.details', $batch->batch_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $batches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection