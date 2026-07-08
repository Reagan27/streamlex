@php
    $userName = auth()->user()->name ?? 'System';
    $rate = (float)($activity->engagement_rate ?? 0);
    $engagement = $activity->engagement_type ?? $activity->engagement ?? 'daily';
    $logs = $activity->logs->sortBy('date');
    $itemRows = [];
    $itemN = 0;
    $workTotal = 0;
    $expTotal = 0;
    foreach ($logs as $log) {
        $dLabel = \Carbon\Carbon::parse($log->date)->format('j M Y');
        $data = (array)($log->log_data ?: []);
        $works = $data['work'] ?? [];
        if (($activity->engagement_type ?? 'daily') === 'daily') {
            $itemN++;
            $desc = collect($works)->map(fn($w) => is_array($w) ? ($w['desc'] ?? '') : $w)->filter()->implode('; ');
            $itemRows[] = ['n'=>$itemN,'date'=>$dLabel,'type'=>'Work','desc'=> $desc ?: 'Day logged','basis'=>"1 day x KES " . number_format($rate),'amount'=>$rate];
            $workTotal += $rate;
        } else {
            foreach ($works as $w) {
                $hours = is_array($w) ? ($w['hours'] ?? 0) : 0;
                if ($hours <= 0) continue;
                $itemN++;
                $desc = is_array($w) ? ($w['desc'] ?? '') : $w;
                $lineAmt = $hours * $rate;
                $itemRows[] = ['n'=>$itemN,'date'=>$dLabel,'type'=>'Work','desc'=>$desc ?: '-','basis'=>"{$hours}h x KES " . number_format($rate),'amount'=>$lineAmt];
                $workTotal += $lineAmt;
            }
        }

        foreach ($data['expenses'] ?? [] as $e) {
            $itemN++;
            $amt = floatval($e['amount'] ?? 0);
            $itemRows[] = ['n'=>$itemN,'date'=>$dLabel,'type'=>'Expense','desc'=>($e['desc'] ?? '-') . ' (' . ($e['cat'] ?? 'Other') . ')','basis'=>'-','amount'=>$amt];
            $expTotal += $amt;
        }

        foreach ($data['logistics'] ?? [] as $lg) {
            $itemN++;
            $cost = floatval($lg['cost'] ?? 0);
            $itemRows[] = ['n'=>$itemN,'date'=>$dLabel,'type'=>'Transport','desc'=>($lg['from'] ?? '-') . ' -> ' . ($lg['to'] ?? '-') . ' (' . ($lg['mode'] ?? '-') . ')','basis'=>'-','amount'=>$cost];
            $expTotal += $cost;
        }
    }
    $total = $workTotal + $expTotal;
    $displayStatus = match ($activity->status) {
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'funded' => 'Paid',
        'rejected' => 'Rejected',
        default => ucfirst($activity->status ?? 'Unknown'),
    };
    $paymentStatus = in_array($activity->status, ['funded']) ? 'Paid' : 'Unpaid';
    $engagementLabel = $activity->engagement_type ? ucfirst($activity->engagement_type) : 'Not set';
@endphp
<div class="invoice-root">
    <style>
        .invoice-root{font-family: Arial, Helvetica, sans-serif; color:#1f2937; width:100%; padding:10px;}
        .inv-header{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px solid #e6eef6;padding-bottom:14px;margin-bottom:16px;gap:12px;flex-wrap:wrap}
        .inv-title{font-size:20px;font-weight:700;color:#0f4c81}
        .inv-sub{font-size:12px;color:#64748b;margin-top:6px}
        .inv-meta{font-size:12px;color:#64748b;text-align:right}
        .inv-grid{width:100%;margin:12px 0 18px 0;font-size:13px}
        .inv-grid td{padding:6px 0;color:#475569}
        .inv-label{color:#64748b;width:140px}
        .items-title{font-size:12px;font-weight:700;color:#0f4c81;margin-bottom:8px;text-transform:uppercase}
        .items-table{width:100%;border-collapse:collapse;font-size:11px;margin-bottom:12px;table-layout:fixed;}
        .items-table thead th{background:#f8fafc;border-bottom:1px solid #e6eef6;padding:6px;text-align:left;color:#334155;font-size:11px}
        .items-table tbody td{padding:8px 6px;border-bottom:1px solid #eef2f7;vertical-align:top}
        .items-table, .items-table th, .items-table td { box-sizing: border-box; }
        .items-table th, .items-table td { word-break: break-word; overflow-wrap: anywhere; white-space: normal; }
        .text-right{text-align:right}
        .totals{width:100%;margin-top:6px}
        .totals td{padding:8px;border-top:1px solid #eef2f7}
        .total-row{font-weight:700;background:#f8fafc}
        .total-amount{font-size:16px;color:#0f4c81;font-weight:800}
        .inv-footer{margin-top:18px;padding-top:12px;border-top:1px solid #e6eef6;font-size:11px;color:#8892a8}
    </style>

    <div class="inv-header">
        <div>
            <div class="inv-title">Field Activity Invoice</div>
            <div class="inv-sub">{{ $activity->title }}</div>
            <div class="inv-sub" style="margin-top:6px">Invoice No: <strong>{{ 'FAM-INV-' . str_pad($activity->id,5,'0',STR_PAD_LEFT) }}</strong></div>
        </div>
        <div class="inv-meta">
            <div>Generated: {{ now()->format('j M Y H:i') }}</div>
            <div>By: {{ $userName }}</div>
        </div>
    </div>

    <table class="inv-grid">
        <tr><td class="inv-label">Personnel</td><td style="font-weight:600">{{ $activity->user->name ?? '-' }}</td></tr>
        <tr><td class="inv-label">Engagement</td><td style="font-weight:600">{{ $engagementLabel }} - KES {{ number_format($rate) }}</td></tr>
        <tr><td class="inv-label">Status</td><td style="font-weight:600">{{ $displayStatus }}</td></tr>
        <tr><td class="inv-label">Payment</td><td style="font-weight:600">{{ $paymentStatus }}</td></tr>
        <tr><td class="inv-label">Period</td><td style="font-weight:600">{{ \Carbon\Carbon::parse($activity->start_date)->format('j M Y') }} - {{ \Carbon\Carbon::parse($activity->end_date)->format('j M Y') }}</td></tr>
        <tr><td class="inv-label">Location</td><td style="font-weight:600">{{ $activity->location ?? '-' }}</td></tr>
    </table>

    @php
        $workItems = collect($itemRows)->where('type', 'Work')->all();
        $expenseItems = collect($itemRows)->where('type', 'Expense')->all();
        $logisticsItems = collect($itemRows)->where('type', 'Transport')->all();
    @endphp

    <div class="items-title">Work done</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:18%">Date</th>
                <th style="width:60%">Description</th>
                <th style="width:22%" class="text-right">Amount (KES)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workItems as $r)
                <tr>
                    <td>{{ $r['date'] }}</td>
                    <td>{{ $r['desc'] }}<br><small>{{ $r['basis'] }}</small></td>
                    <td class="text-right">{{ number_format($r['amount']) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="padding:14px;text-align:center;color:#64748b">No work entries logged</td></tr>
            @endforelse
        </tbody>
    </table>

    @if(count($expenseItems))
        <div class="items-title">General Expenses</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:18%">Date</th>
                    <th style="width:60%">Description</th>
                    <th style="width:22%" class="text-right">Amount (KES)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenseItems as $r)
                    <tr>
                        <td>{{ $r['date'] }}</td>
                        <td>{{ $r['desc'] }}</td>
                        <td class="text-right">{{ number_format($r['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(count($logisticsItems))
        <div class="items-title">Transport &amp; Logistics</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:18%">Date</th>
                    <th style="width:60%">Description</th>
                    <th style="width:22%" class="text-right">Amount (KES)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logisticsItems as $r)
                    <tr>
                        <td>{{ $r['date'] }}</td>
                        <td>{{ $r['desc'] }}</td>
                        <td class="text-right">{{ number_format($r['amount']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="totals">
        <tbody>
            <tr><td style="width:72%">Work Logged Subtotal</td><td class="text-right">{{ number_format($workTotal) }}</td></tr>
            <tr><td>Expenses &amp; Logistics Subtotal</td><td class="text-right">{{ number_format($expTotal) }}</td></tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>Total Payable (KES)</td>
                <td class="text-right total-amount">{{ number_format($total) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="inv-footer">
        <div style="display:flex;justify-content:space-between">
            <div>This is a system-generated invoice from the Field Activity Management platform.</div>
            <div>{{ 'FAM-INV-' . str_pad($activity->id,5,'0',STR_PAD_LEFT) }}</div>
        </div>
    </div>
</div>