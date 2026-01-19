@extends('layouts.app')

@section('page-title', __('Training Attendance History'))
@section('page-heading', $event->name . ' - ' . __('Attendance History'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('training.index') }}">Training Events</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('training.show', $event->id) }}">{{ $event->name }}</a>
    </li>
    <li class="breadcrumb-item active">
        Attendance History
    </li>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daily Attendance Records</h5>
                    <div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-success dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" 
                                       href="{{ route('training.export', $event->id) }}">
                                        <i class="fas fa-file-pdf"></i> Export as PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" 
                                       href="{{ route('training.export.excel', $event->id) }}">
                                        <i class="fas fa-file-excel"></i> Export as Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('training.show', $event->id) }}" class="btn btn-secondary">
                            Back to Event
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($attendances as $date => $dailyAttendances)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">{{ Carbon\Carbon::parse($date)->format('F d, Y') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Name</th>
                                                <th>ID Number</th>
                                                <th>Days Attended</th>
                                                <th>Amount</th>
                                                <th>Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dailyAttendances as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->created_at->format('H:i') }}</td>
                                                    <td>{{ $attendance->name }}</td>
                                                    <td>{{ $attendance->id_number }}</td>
                                                    <td>{{ $attendance->days_attended }}</td>
                                                    <td>{{ number_format($attendance->total_amount, 2) }}</td>
                                                    <td>
                                                        <img src="{{ $attendance->signature }}" 
                                                             alt="signature" 
                                                             style="max-width: 100px; height: auto;">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No attendance records found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Summary</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Start Date</dt>
                        <dd class="col-sm-8">{{ $event->start_date->format('M d, Y') }}</dd>

                        <dt class="col-sm-4">End Date</dt>
                        <dd class="col-sm-8">{{ $event->end_date->format('M d, Y') }}</dd>

                        <dt class="col-sm-4">Total Days</dt>
                        <dd class="col-sm-8">{{ $event->total_days }}</dd>

                        <dt class="col-sm-4">Daily Amount</dt>
                        <dd class="col-sm-8">{{ number_format($event->daily_amount, 2) }}</dd>

                        <dt class="col-sm-4">Total Attendees</dt>
                        <dd class="col-sm-8">{{ $attendances->flatten()->unique('id_number')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Attendance Statistics</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Total Check-ins</dt>
                        <dd class="col-sm-6">{{ $attendances->flatten()->count() }}</dd>

                        <dt class="col-sm-6">Average Daily Attendance</dt>
                        <dd class="col-sm-6">
                            {{ number_format($attendances->count() > 0 ? 
                                $attendances->flatten()->count() / $attendances->count() : 0, 1) }}
                        </dd>

                        <dt class="col-sm-6">Completion Rate</dt>
                        <dd class="col-sm-6">
                            {{ number_format($attendances->flatten()->unique('id_number')
                                ->where('completed', true)->count() / 
                                max($attendances->flatten()->unique('id_number')->count(), 1) * 100, 1) }}%
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection