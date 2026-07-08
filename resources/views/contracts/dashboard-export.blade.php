<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contracts Dashboard Export</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        .summary { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f7f7f7; }
    </style>
</head>
<body>
    <h1>Contracts Dashboard</h1>
    <div class="summary">
        <strong>Total contracts:</strong> {{ $data['summary']['total'] }}<br>
        <strong>Active contracts:</strong> {{ $data['summary']['active'] }}<br>
        <strong>Pending signatures:</strong> {{ $data['summary']['pending_signatures'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Signature Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['contracts'] as $contract)
                <tr>
                    <td>{{ $contract->title }}</td>
                    <td>{{ $contract->status }}</td>
                    <td>{{ $contract->start_date ? $contract->start_date->format(config('app.date_format')) : 'N/A' }}</td>
                    <td>{{ $contract->end_date ? $contract->end_date->format(config('app.date_format')) : 'N/A' }}</td>
                    <td>{{ optional($contract->userContractSignatures->first())->status ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
