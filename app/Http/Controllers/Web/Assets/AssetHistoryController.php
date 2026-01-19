<?php

namespace Vanguard\Http\Controllers\Web\Assets;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\AssetAssignment;
use Vanguard\AssetDistribution;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AssetHistoryController extends Controller
{
    /**
     * Display asset history with search and filter capabilities
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $condition = $request->input('condition');
        $status = $request->input('status');

        // Single Assignments Query
        $singleAssignments = DB::table('asset_assignments as aa')
            ->join('users as assigned_to', 'aa.assigned_to', '=', 'assigned_to.id')
            ->join('users as assigned_by', 'aa.assigned_by', '=', 'assigned_by.id')
            ->join('assets', 'aa.asset_id', '=', 'assets.id')
            ->select(
                'aa.*',
                'assets.name as asset_name',
                'assigned_to.first_name as assigned_to_name',
                'assigned_to.last_name as assigned_to_lastname',
                'assigned_by.first_name as assigned_by_name',
                'assigned_by.last_name as assigned_by_lastname'
            )
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('aa.imei_number', 'like', "%{$search}%")
                      ->orWhere('aa.serial_number', 'like', "%{$search}%")
                      ->orWhere('assets.name', 'like', "%{$search}%")
                      ->orWhere('assigned_to.first_name', 'like', "%{$search}%")
                      ->orWhere('assigned_to.last_name', 'like', "%{$search}%")
                      ->orWhere('assigned_by.first_name', 'like', "%{$search}%")
                      ->orWhere('assigned_by.last_name', 'like', "%{$search}%");
                });
            })
            ->when($condition, function ($query) use ($condition) {
                return $query->where('aa.physical_condition', $condition);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('aa.assignment_status', $status);
            })
            ->orderBy('aa.created_at', 'desc');

        // Bulk Distributions Query
        $bulkDistributions = DB::table('asset_distributions as ad')
            ->join('users as distributed_to', 'ad.distributed_to', '=', 'distributed_to.id')
            ->join('users as distributed_by', 'ad.distributed_by', '=', 'distributed_by.id')
            ->join('assets', 'ad.asset_id', '=', 'assets.id')
            ->select(
                'ad.*',
                'assets.name as asset_name',
                'distributed_to.first_name as distributed_to_name',
                'distributed_to.last_name as distributed_to_lastname',
                'distributed_by.first_name as distributed_by_name',
                'distributed_by.last_name as distributed_by_lastname'
            )
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('assets.name', 'like', "%{$search}%")
                      ->orWhere('distributed_to.first_name', 'like', "%{$search}%")
                      ->orWhere('distributed_to.last_name', 'like', "%{$search}%")
                      ->orWhere('distributed_by.first_name', 'like', "%{$search}%")
                      ->orWhere('distributed_by.last_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('ad.created_at', 'desc');

        // Get the data with pagination
        $singleAssignments = $singleAssignments->paginate(15, ['*'], 'assignments')
            ->appends(request()->except('assignments'));
        
        $bulkDistributions = $bulkDistributions->paginate(15, ['*'], 'distributions')
            ->appends(request()->except('distributions'));

        // Get available conditions and statuses for filters
        $conditions = AssetAssignment::getPhysicalConditions();
        $statuses = AssetAssignment::getAssignmentStatuses();

        return view('asset.history.index', compact(
            'singleAssignments',
            'bulkDistributions',
            'conditions',
            'statuses',
            'search',
            'condition',
            'status'
        ));
    }

    /**
     * Update IMEI or Serial Number for an assignment
     *
     * @param Request $request
     * @param AssetAssignment $assignment
     * @return JsonResponse
     */
    public function updateSerialNumber(Request $request, AssetAssignment $assignment): JsonResponse
    {
        try {
            $this->validate($request, [
                'imei_number' => 'nullable|string|max:191',
                'serial_number' => 'nullable|string|max:191'
            ]);

            DB::beginTransaction();

            $assignment->update($request->only(['imei_number', 'serial_number']));

            // Log the change
            Log::info('Asset numbers updated', [
                'assignment_id' => $assignment->id,
                'user_id' => auth()->id(),
                'changes' => $request->only(['imei_number', 'serial_number'])
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Numbers updated successfully',
                'data' => [
                    'imei_number' => $assignment->imei_number,
                    'serial_number' => $assignment->serial_number
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating asset numbers', [
                'error' => $e->getMessage(),
                'assignment_id' => $assignment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating numbers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export asset history to CSV/Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'assignments');
        $format = $request->input('format', 'csv');

        try {
            if ($type === 'assignments') {
                $query = DB::table('asset_assignments as aa')
                    ->join('users as assigned_to', 'aa.assigned_to', '=', 'assigned_to.id')
                    ->join('users as assigned_by', 'aa.assigned_by', '=', 'assigned_by.id')
                    ->join('assets', 'aa.asset_id', '=', 'assets.id')
                    ->select(
                        'assets.name as Asset',
                        'aa.imei_number as IMEI',
                        'aa.serial_number as Serial',
                        DB::raw("CONCAT(assigned_to.first_name, ' ', assigned_to.last_name) as Assigned_To"),
                        DB::raw("CONCAT(assigned_by.first_name, ' ', assigned_by.last_name) as Assigned_By"),
                        'aa.physical_condition as Condition',
                        'aa.assignment_status as Status',
                        'aa.comments as Comments',
                        'aa.created_at as Date'
                    );
            } else {
                $query = DB::table('asset_distributions as ad')
                    ->join('users as distributed_to', 'ad.distributed_to', '=', 'distributed_to.id')
                    ->join('users as distributed_by', 'ad.distributed_by', '=', 'distributed_by.id')
                    ->join('assets', 'ad.asset_id', '=', 'assets.id')
                    ->select(
                        'assets.name as Asset',
                        DB::raw("CONCAT(distributed_to.first_name, ' ', distributed_to.last_name) as Distributed_To"),
                        DB::raw("CONCAT(distributed_by.first_name, ' ', distributed_by.last_name) as Distributed_By"),
                        'ad.quantity as Quantity',
                        'ad.comments as Comments',
                        'ad.created_at as Date'
                    );
            }

            $data = $query->get();

            return $this->generateExport($data, $type, $format);

        } catch (Exception $e) {
            Log::error('Export error:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Generate export file
     *
     * @param \Illuminate\Support\Collection $data
     * @param string $type
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function generateExport($data, $type, $format)
    {
        $filename = "asset_{$type}_" . date('Y-m-d_His');
        
        if ($format === 'csv') {
            return response()->streamDownload(function() use ($data) {
                $handle = fopen('php://output', 'w');
                // Add headers
                fputcsv($handle, array_keys((array)$data->first()));
                // Add data
                foreach ($data as $row) {
                    fputcsv($handle, (array)$row);
                }
                fclose($handle);
            }, $filename . '.csv');
        }

        // Add more export formats as needed
    }
}