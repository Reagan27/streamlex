<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Services\ContractDashboardService;

class ContractDashboardController extends Controller
{
    public function __construct(protected ContractDashboardService $dashboardService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'county', 'project', 'role', 'status', 'signature_status', 'date_range', 'date_from', 'date_to']);
        $data = $this->dashboardService->getDashboardData($request->user(), $filters);

        return view('contracts.dashboard', $data);
    }

    public function export(Request $request, string $type)
    {
        $filters = $request->only(['search', 'county', 'project', 'role', 'status', 'signature_status', 'date_range', 'date_from', 'date_to']);
        $data = $this->dashboardService->getDashboardData($request->user(), $filters);
        $filename = 'contracts-dashboard-' . Carbon::now()->format('Ymd_His');

        if ($type === 'csv') {
            $rows = [];
            $rows[] = ['Title', 'Status', 'Start Date', 'End Date', 'County', 'Role', 'Signature Status'];

            foreach ($data['contracts'] as $contract) {
                $signatureStatus = optional($contract->userContractSignatures->first())->status ?? 'N/A';
                $counties = $contract->counties->pluck('name')->join(', ');
                $role = optional($contract->role)->display_name ?? 'N/A';

                $rows[] = [
                    $contract->title,
                    $contract->status,
                    $contract->start_date ? $contract->start_date->format(config('app.date_format')) : 'N/A',
                    $contract->end_date ? $contract->end_date->format(config('app.date_format')) : 'N/A',
                    $counties,
                    $role,
                    $signatureStatus,
                ];
            }

            $handle = fopen('php://temp', 'r+');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return response($content, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ]);
        }

        if ($type === 'xlsx') {
            $rows = [];
            $rows[] = ['Title', 'Status', 'Start Date', 'End Date', 'County', 'Role', 'Signature Status'];
            foreach ($data['contracts'] as $contract) {
                $rows[] = [
                    $contract->title,
                    $contract->status,
                    $contract->start_date ? $contract->start_date->format(config('app.date_format')) : 'N/A',
                    $contract->end_date ? $contract->end_date->format(config('app.date_format')) : 'N/A',
                    $contract->counties->pluck('name')->join(', '),
                    optional($contract->role)->display_name ?? 'N/A',
                    optional($contract->userContractSignatures->first())->status ?? 'N/A',
                ];
            }

            $export = new class($rows) implements FromArray {
                public function __construct(protected array $rows)
                {
                }

                public function array(): array
                {
                    return $this->rows;
                }
            };

            return Excel::download($export, $filename . '.xlsx');
        }

        $html = view('contracts.dashboard-export', ['data' => $data])->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output($filename . '.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"',
        ]);
    }
}
