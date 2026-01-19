<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $event->name }} - Attendances</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .event-details {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .signature-img {
            max-width: 100px;
            max-height: 50px;
        }
        .attendance-dates {
            font-size: 10px;
            color: #6c757d;
        }
        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }
        .status-progress {
            color: #007bff;
            font-weight: bold;
        }
        .status-banned {
            color: #dc3545;
            font-weight: bold;
        }
        /* Header/Footer Styles */
        @page {
            header: html_eventHeader;
            footer: html_eventFooter;
        }
    </style>
</head>
<body>
    <!-- Define Header -->
    <htmlpageheader name="eventHeader">
        <div class="header">
            <h2 style="margin:0;">{{ $event->name }}</h2>
        </div>
    </htmlpageheader>

    <!-- Define Footer -->
    <htmlpagefooter name="eventFooter">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="text-align: left; border: none;">Generated on: {{ now()->format('M d, Y H:i:s') }}</td>
                <td style="text-align: right; border: none;">Page {PAGENO} of {nbpg}</td>
            </tr>
        </table>
    </htmlpagefooter>

    <!-- Summary Section -->
    <div class="summary-section">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <h3>Event Details</h3>
                    <p><strong>Start Date:</strong> {{ $event->start_date->format('M d, Y') }}</p>
                    <p><strong>End Date:</strong> {{ $event->end_date->format('M d, Y') }}</p>
                    <p><strong>Total Days:</strong> {{ $totalDays }}</p>
                    <p><strong>Daily Amount:</strong> {{ number_format($event->daily_amount, 2) }}</p>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <h3>Attendance Summary</h3>
                    @php
                        $bannedCount = $attendees->filter(function($attendee) {
                            return \Vanguard\BannedAttendee::where('id_number', $attendee->id_number)->exists();
                        })->count();
                    @endphp
                    <p><strong>Total Registered:</strong> {{ $attendees->count() }}</p>
                    <p><strong>Completed Training:</strong> {{ $attendees->where('completed', true)->count() }}</p>
                    <p><strong>In Progress:</strong> {{ $attendees->where('completed', false)->count() }}</p>
                    <p><strong>Banned:</strong> {{ $bannedCount }}</p>
                    <p><strong>Total Amount:</strong> {{ number_format($attendees->sum('total_amount'), 2) }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Attendees Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>ID Number</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Days</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendees as $attendee)
                @php
                    $isBanned = \Vanguard\BannedAttendee::where('id_number', $attendee->id_number)->first();
                @endphp
                <tr>
                    <td>{{ $attendee->name }}</td>
                    <td>{{ $attendee->id_number }}</td>
                    <td>{{ $attendee->phone_number }}</td>
                    <td>{{ $attendee->email }}</td>
                    <td>
                        {{ $attendee->days_attended }}
                        <div class="attendance-dates">
                            {{ implode(', ', $attendanceDates[$attendee->id_number]) }}
                        </div>
                    </td>
                    <td>{{ number_format($attendee->total_amount, 2) }}</td>
                    <td class="{{ $isBanned ? 'status-banned' : ($attendee->completed ? 'status-completed' : 'status-progress') }}">
                        @if($isBanned)
                            Banned
                            <div class="attendance-dates">
                                Reason: {{ $isBanned->reason }}
                                <br>
                                Date: {{ $isBanned->banned_at->format('M d, Y') }}
                            </div>
                        @elseif($attendee->completed)
                            Completed
                        @else
                            In Progress
                        @endif
                    </td>
                    <td>
                        <img src="{{ $attendee->signature }}" class="signature-img">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>