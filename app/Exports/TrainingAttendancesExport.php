<?php

namespace Vanguard\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Vanguard\BannedAttendee;
use Vanguard\TrainingEvent;


class TrainingAttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    private $event;

    public function __construct(TrainingEvent $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event->attendances()
            ->orderBy('id_number')
            ->orderBy('created_at')
            ->get()
            ->groupBy('id_number')
            ->map(function($attendances) {
                $latest = $attendances->last();
                $isBanned = BannedAttendee::where('id_number', $latest->id_number)->first();
                if ($isBanned) {
                    $latest->banned_reason = $isBanned->reason;
                    $latest->banned_at = $isBanned->banned_at;
                }
                return $latest;
            });
    }

    public function headings(): array
    {
        return [
            'Name',
            'ID Number',
            'Phone Number',
            'Email',
            'Days Attended',
            'Total Amount',
            'Status',
            'Last Attendance',
            'Ban Status',
            'Ban Reason',
            'Banned Date'
        ];
    }

    public function map($row): array
    {
        $isBanned = isset($row->banned_reason);
        $status = $isBanned ? 'Banned' : ($row->completed ? 'Completed' : 'In Progress');

        return [
            $row->name,
            $row->id_number,
            $row->phone_number,
            $row->email,
            $row->days_attended,
            number_format($row->total_amount, 2),
            $status,
            $row->created_at->format('Y-m-d H:i'),
            $isBanned ? 'Yes' : 'No',
            $isBanned ? $row->banned_reason : '-',
            $isBanned ? $row->banned_at->format('Y-m-d H:i') : '-'
        ];
    }
}