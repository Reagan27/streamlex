@extends('layouts.pdf')

@section('page-title', 'Back to Office Report PDF')
@section('content')
<div style="font-family: Arial, sans-serif; font-size: 12px;">
    <h2 style="text-align: center;">Back to Office Report</h2>
    <hr>
    <strong>Project Name:</strong> {{ $report->project_name ?? 'N/A' }}<br>
    <strong>Date:</strong> {{ $report->activity_date?->format('M d, Y') ?? 'N/A' }}<br>
    <strong>Activity:</strong> {{ $report->activity ?? 'N/A' }}<br>
    <strong>Venue:</strong> {{ $report->venue ?? 'N/A' }}<br>
    <hr>
    <strong>Participants:</strong>
    <table border="1" cellpadding="4" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Category</th>
                <th>18–35 Years</th>
                <th>35–59 Years</th>
                <th>Above 60 Years</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['male' => 'Male', 'female' => 'Female', 'disability' => 'Disability', 'vmgs' => 'VMGs'] as $key => $cat)
            <tr>
                <td>{{ $cat }}</td>
                @for($i=0; $i<3; $i++)
                <td>{{ $report->participants[$key][$i] ?? '' }}</td>
                @endfor
                <td>{{ $report->participants[$key]['total'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <strong>Introduction:</strong>
    <p>{!! nl2br(e($report->introduction)) !!}</p>
    <strong>Objective:</strong>
    <p>{!! nl2br(e($report->objective)) !!}</p>
    <hr>
    <strong>Budget / Expenditure:</strong>
    <table border="1" cellpadding="4" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Item</th>
                <th>Planned</th>
                <th>Actual</th>
                <th>Variance</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($report->budget['item']) && is_array($report->budget['item']))
                @foreach($report->budget['item'] as $i => $item)
                <tr>
                    <td>{{ $item }}</td>
                    <td>{{ $report->budget['planned'][$i] ?? '' }}</td>
                    <td>{{ $report->budget['actual'][$i] ?? '' }}</td>
                    <td>{{ $report->budget['variance'][$i] ?? '' }}</td>
                    <td>{{ $report->budget['comment'][$i] ?? '' }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <hr>
    <strong>Output:</strong>
    <p>{!! nl2br(e($report->output)) !!}</p>
    <strong>Key Highlights:</strong>
    <p>{!! nl2br(e($report->key_highlights)) !!}</p>
    <strong>Challenges & Risks:</strong>
    <p>{!! nl2br(e($report->challenges_and_risks)) !!}</p>
    <strong>Best Practices:</strong>
    <p>{!! nl2br(e($report->best_practices)) !!}</p>
    <strong>Lessons Learnt:</strong>
    <p>{!! nl2br(e($report->lessons_learnt)) !!}</p>
    <strong>Recommendations:</strong>
    <p>{!! nl2br(e($report->recommendations)) !!}</p>
    <strong>Way Forward:</strong>
    <p>{!! nl2br(e($report->way_forward)) !!}</p>
    <hr>
    <strong>Annexes:</strong>
    <ul>
        <li>Attendance list</li>
        <li>Pictures on activity</li>
        <li>Minutes of Decision / Agreement</li>
        <li>Receipts</li>
    </ul>
    @if($report->attachments->count())
    <strong>Attachments:</strong>
    <ul>
        @foreach($report->attachments as $attachment)
        <li>
            {{ $attachment->original_name }}
            @if($attachment->file_path)
                <br>
                <small style="font-size:10px;">
                    <a href="{{ asset('storage/' . ltrim($attachment->file_path, '/')) }}">Download/View</a>
                </small>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
    <hr>
    <strong>Created By:</strong> {{ $report->creator->name }}<br>
    <strong>Created At:</strong> {{ $report->created_at->format('M d, Y H:i') }}<br>
    <strong>County:</strong> {{ $report->county->name ?? 'N/A' }}<br>
    @if($report->status === 'approved')
    <strong>Approved By:</strong> {{ $report->approver->name ?? 'N/A' }}<br>
    <strong>Approved At:</strong> {{ $report->approved_at?->format('M d, Y H:i') ?? 'N/A' }}<br>
    @endif
</div>
@endsection
