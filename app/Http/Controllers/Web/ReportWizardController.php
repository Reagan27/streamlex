<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanguard\Asset;
use Vanguard\Http\Controllers\Controller;
use Vanguard\User;
use Vanguard\County;
use Vanguard\Support\Enum\UserStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Vanguard\AdminContract;
use Vanguard\EventAttendance;
use Vanguard\IssuesCategory;
use Vanguard\Payment;
use Vanguard\Role;
use Vanguard\PaymentCycle;
use Vanguard\SupportIssue;
use Vanguard\UserContractSignature;

class ReportWizardController extends Controller
{

    public function index()
    {
        $userColumns = $this->getUserColumns();
        $assetColumns = $this->getAssetColumns();
        $assetAssignmentColumns = $this->getAssetAssignmentColumns();
        $payrollColumns = $this->getPayrollColumns();
        $trainingColumns = $this->getTrainingColumns(); 
        $contractColumns = $this->getContractColumns();
        $contractStatuses = $this->getContractStatuses();
        $supportIssueColumns = $this->getSupportIssueColumns();
        $masterPayrollColumns = $this->getMasterPayrollColumns();
        $counties = County::pluck('name', 'id')->toArray();
        $contractStatuses = $this->getContractStatuses();
        $userStatuses = UserStatus::lists();
        $contractTitles = AdminContract::pluck('title', 'title')->toArray();
        $roles = Role::pluck('name', 'id')->toArray();
        $completedStatuses = $this->getCompletedStatuses();
        $paymentCycles = PaymentCycle::pluck('description', 'id')->toArray();
        $paymentStatuses = $this->getPaymentStatuses();
        $trainingStatuses = $this->getTrainingStatuses();
        $issueCategories = IssuesCategory::pluck('name', 'id')->toArray(); 
    $issueStatuses = $this->getIssueStatuses(); 
    $issuePriorities = $this->getIssuePriorities(); 
        
        return view('report_wizard.index', compact(
            'userColumns', 'assetColumns', 'assetAssignmentColumns', 'payrollColumns', 'trainingColumns',
            'counties', 'contractStatuses','contractColumns', 'userStatuses', 'roles',
            'completedStatuses', 'paymentCycles', 'paymentStatuses', 'trainingStatuses','contractTitles',
            'supportIssueColumns', 'issueCategories', 'issueStatuses', 'issuePriorities','masterPayrollColumns'
        ));
    }

    public function generateReport(Request $request)
    {
        try {
            Log::info('Report request received', [
                'request_data' => $request->all()
            ]);
    
            $validated = $request->validate([
                'type' => 'required|string|in:users,assets,asset_assignments,payroll,training,contracts,support_issues,master_payroll',
                'filters' => 'array',
                'columns' => 'required|array|min:1',
                'page' => 'integer|min:1|nullable',
                'download' => 'boolean|nullable'
            ]);
    
            $type = $validated['type'];
            $filters = $validated['filters'] ?? [];
            $columns = $validated['columns'];
            $isDownload = $request->boolean('download', false);
            
            $query = $this->buildQuery($type, $columns);
            
            if (!$query) {
                throw new \Exception('Failed to build query');
            }
    
            $this->applyFilters($query, $type, $filters);
    
            if ($isDownload) {
                $data = $query->get();
                
                // Format master payroll data if applicable
                if ($type === 'master_payroll') {
                    $data = $this->formatMasterPayrollData($data);
                }
                
                $transformedColumns = $this->transformColumns($columns, $type);
    
                return response()->json([
                    'columns' => $transformedColumns,
                    'data' => $data,
                    'total' => $data->count()
                ]);
            }
            
            $perPage = 30;
            $data = $query->paginate($perPage);
            
            // Format master payroll data if applicable
            if ($type === 'master_payroll') {
                $items = $this->formatMasterPayrollData($data->items());
                // Create a new paginator with formatted items
                $data = new \Illuminate\Pagination\LengthAwarePaginator(
                    $items,
                    $data->total(),
                    $data->perPage(),
                    $data->currentPage(),
                    ['path' => request()->url()]
                );
            }
            
            $transformedColumns = $this->transformColumns($columns, $type);
    
            return response()->json([
                'columns' => $transformedColumns,
                'data' => $type === 'master_payroll' ? $items : $data->items(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
                'per_page' => $data->perPage()
            ]);
    
        } catch (\Exception $e) {
            Log::error('Report generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
    
            return response()->json([
                'error' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildTrainingQuery($columns)
    {
        try {
            // Create a subquery to get the latest attendance for each person/event
            $latestAttendances = EventAttendance::query()
                ->select('id_number', 'training_event_id', 
                    DB::raw('MAX(created_at) as latest_attendance'))
                ->groupBy('id_number', 'training_event_id');
    
            $query = EventAttendance::query()
                ->joinSub($latestAttendances, 'latest_att', function ($join) {
                    $join->on('event_attendances.id_number', '=', 'latest_att.id_number')
                        ->on('event_attendances.training_event_id', '=', 'latest_att.training_event_id')
                        ->on('event_attendances.created_at', '=', 'latest_att.latest_attendance');
                })
                ->join('training_events', 'event_attendances.training_event_id', '=', 'training_events.id')
                ->join('counties', 'training_events.county_id', '=', 'counties.id');
    
            $selects = [];
            foreach ($columns as $column) {
                switch ($column) {
                    case 'name':
                        $selects[] = DB::raw('event_attendances.name as attendee_name');
                        break;
                    case 'id_number':
                        $selects[] = 'event_attendances.id_number';
                        break;
                    case 'phone_number':
                        $selects[] = 'event_attendances.phone_number';
                        break;
                    case 'email':
                        $selects[] = 'event_attendances.email';
                        break;
                    case 'designation':
                        $selects[] = 'event_attendances.designation';
                        break;
                    case 'days_attended':
                        $selects[] = 'event_attendances.days_attended';
                        break;
                    case 'total_amount':
                        $selects[] = 'event_attendances.total_amount';
                        break;
                    case 'completed':
                        $selects[] = DB::raw("CASE WHEN event_attendances.completed = 1 THEN 'Completed' ELSE 'Incomplete' END as completed");
                        break;
                    case 'phone_verified':
                        $selects[] = DB::raw("CASE WHEN event_attendances.phone_verified = 1 THEN 'Verified' ELSE 'Not Verified' END as phone_verified");
                        break;
                    case 'event_name':
                        $selects[] = 'training_events.name as event_name';
                        break;
                    case 'venue_name':
                        $selects[] = 'training_events.venue_name';
                        break;
                    case 'county_name':
                        $selects[] = 'counties.name as county_name';
                        break;
                    case 'start_date':
                        $selects[] = DB::raw('DATE_FORMAT(training_events.start_date, "%Y-%m-%d") as start_date');
                        break;
                    case 'end_date':
                        $selects[] = DB::raw('DATE_FORMAT(training_events.end_date, "%Y-%m-%d") as end_date');
                        break;
                    case 'daily_amount':
                        $selects[] = 'training_events.daily_amount';
                        break;
                    case 'created_at':
                        $selects[] = DB::raw('DATE_FORMAT(event_attendances.created_at, "%Y-%m-%d %H:%i:%s") as created_at');
                        break;
                }
            }
    
            $query->select($selects);
    
            Log::info('Training Query Built', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
    
            return $query;
    
        } catch (\Exception $e) {
            Log::error('Error in buildTrainingQuery', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'columns' => $columns
            ]);
            throw $e;
        }
    }


    


    private function formatStatusSummary($summary)
{
    if (!$summary) return [];
    
    $statuses = array_filter(explode(',', $summary)); 
    return array_map(function($status) {
        if (preg_match('/^(.*?)\s*\((\d+)\)$/', trim($status), $matches)) {
            return [
                'status' => trim($matches[1]),
                'count' => (int)$matches[2]
            ];
        }
        return null;
    }, $statuses);
}



    private function buildContractQuery($columns)
    {
        $query = AdminContract::query()
            ->join('user_contract_signatures', 'admin_contracts.id', '=', 'user_contract_signatures.contract_id')
            ->join('users', 'user_contract_signatures.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('counties', 'users.county_id', '=', 'counties.id')
            ->leftJoin('user_documents', 'users.id', '=', 'user_documents.user_id')
            ->leftJoin('user_bank_details', 'users.id', '=', 'user_bank_details.user_id')
            ->leftJoin('banks', 'user_bank_details.bank_id', '=', 'banks.id')
            ->leftJoin('user_manual_bank_details', 'users.id', '=', 'user_manual_bank_details.user_id')
            ->leftJoin('bank_branches', function($join) {
                $join->on('user_bank_details.bank_id', '=', 'bank_branches.bank_id')
                     ->on(DB::raw('BINARY user_bank_details.bank_branch COLLATE utf8mb4_unicode_ci'), 
                         '=',
                         DB::raw('BINARY bank_branches.branch_code COLLATE utf8mb4_unicode_ci'));
            })
            ->selectRaw("
                CASE 
                    WHEN user_manual_bank_details.use_manual_details = 1 THEN user_manual_bank_details.manual_branch_name
                    ELSE bank_branches.branch_name 
                END as branch_name,
                CASE 
                    WHEN user_manual_bank_details.use_manual_details = 1 THEN user_manual_bank_details.manual_branch_code
                    ELSE user_bank_details.bank_branch
                END as branch_code
            ");
    
        $selects = [];
        foreach ($columns as $column) {
            switch ($column) {
                case 'contract_title':
                    $selects[] = 'admin_contracts.title as contract_title';
                    break;
                case 'user_name':
                    $selects[] = DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name");
                    break;
                case 'user_role':
                    $selects[] = 'roles.display_name as user_role';
                    break;
                case 'user_county':
                    $selects[] = 'counties.name as user_county';
                    break;
                case 'status':
                    $selects[] = 'user_contract_signatures.status';
                    break;
                case 'start_date':
                    $selects[] = DB::raw('DATE_FORMAT(admin_contracts.start_date, "%Y-%m-%d") as start_date');
                    break;
                case 'end_date':
                    $selects[] = DB::raw('DATE_FORMAT(admin_contracts.end_date, "%Y-%m-%d") as end_date');
                    break;
                case 'days_remaining':
                    $selects[] = DB::raw('DATEDIFF(admin_contracts.end_date, CURDATE()) as days_remaining');
                    break;
                case 'phone':
                    $selects[] = 'users.phone';
                    break;
                case 'id_number':
                    $selects[] = 'user_documents.id_number';
                    break;
                case 'kra_pin':
                    $selects[] = 'user_documents.kra_pin';
                    break;
                case 'bank_name':
                    $selects[] = 'banks.name as bank_name';
                    break;
                case 'account_number':
                    $selects[] = 'user_bank_details.account_number';
                    break;
                case 'branch_code':
                case 'branch_name':
                    break;
            }
        }
    
        if (!empty($selects)) {
            $query->addSelect($selects);
        }
        return $query;
    }

private function getContractColumns()
{
    return [
        'contract_title' => 'Contract Title',
        'user_name' => 'User Name',
        'user_role' => 'Role',
        'user_county' => 'County',
        'status' => 'Status',
        'start_date' => 'Start Date',
        'end_date' => 'End Date', 
        'days_remaining' => 'Days Remaining',
        'phone' => 'Phone',
        'id_number' => 'ID Number',
        'kra_pin' => 'KRA PIN',
        'bank_name' => 'Bank',
        'account_number' => 'Account Number',
        'branch_code' => 'Branch Code',
        'branch_name' => 'Branch Name'

    ];
}

private function getContractFilters()
{
    return [
        'title' => AdminContract::pluck('title', 'title')->toArray(),
        'status' => $this->getContractStatuses(),
        'county_id' => County::pluck('name', 'id')->toArray(),
        'role_id' => Role::pluck('display_name', 'id')->toArray(),
        'date_range' => 'date'
    ];
}
    
    private function buildQuery($type, $columns)
    {
        switch ($type) {
            case 'users':
                return $this->buildUserQuery($columns);
            case 'assets':
                return $this->buildAssetQuery($columns);
            case 'asset_assignments':
                return $this->buildAssetAssignmentQuery($columns);
            case 'payroll':
                return $this->buildPayrollQuery($columns);
            case 'master_payroll':
                return $this->buildMasterPayrollQuery($columns);
            case 'training':
                return $this->buildTrainingQuery($columns);
            case 'contracts':
                return $this->buildContractQuery($columns);
            case 'support_issues':
                return $this->buildSupportIssueQuery($columns);
            default:
                throw new \Exception("Invalid report type");
        }
    }
    
private function applyAssetAssignmentFilters($query, $filter, $value)
{
    switch ($filter) {
        case 'physical_condition':
            $query->where('asset_assignments.physical_condition', $value);
            break;
        case 'assignment_status':
            $query->where('asset_assignments.assignment_status', $value);
            break;
        case 'county_id':
            $query->where('assigned_to_user.county_id', $value);
            break;
        case 'role_id':
            $query->where('assigned_to_user.role_id', $value);
            break;
        default:
            // Handle text search across multiple fields
            $query->where(function ($q) use ($value) {
                $q->where('assets.name', 'LIKE', "%{$value}%")
                  ->orWhere('asset_assignments.imei_number', 'LIKE', "%{$value}%")
                  ->orWhere('asset_assignments.serial_number', 'LIKE', "%{$value}%")
                  ->orWhere(DB::raw("CONCAT(assigned_to_user.first_name, ' ', assigned_to_user.last_name)"), 'LIKE', "%{$value}%")
                  ->orWhere(DB::raw("CONCAT(assigned_by_user.first_name, ' ', assigned_by_user.last_name)"), 'LIKE', "%{$value}%");
            });
    }
}
    
private function applyContractFilters($query, $filter, $value)
{
    switch ($filter) {
        case 'title':
            $query->where('admin_contracts.title', $value);
            break;
        case 'status':
            $query->where('admin_contracts.status', $value);
            break;
        case 'active':
            $query->where('admin_contracts.active_for_onboarding', $value);
            break;
        case 'date_range':
            $dates = explode(' - ', $value);
            if (count($dates) === 2) {
                $query->whereBetween('admin_contracts.created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
            break;
    }
}

private function buildPayrollQuery($columns)
{
    $query = Payment::query()
        ->join('users', 'payments.user_id', '=', 'users.id')
        ->join('payment_cycles', 'payments.payment_cycle_id', '=', 'payment_cycles.id')
        ->join('user_bank_details', 'users.id', '=', 'user_bank_details.user_id')
        ->join('banks', 'user_bank_details.bank_id', '=', 'banks.id')
        ->leftJoin('user_manual_bank_details', 'users.id', '=', 'user_manual_bank_details.user_id')
        ->leftJoin('bank_branches', function($join) {
            $join->on('user_bank_details.bank_id', '=', 'bank_branches.bank_id')
                 ->on(DB::raw('BINARY user_bank_details.bank_branch COLLATE utf8mb4_unicode_ci'), 
                     '=',
                     DB::raw('BINARY bank_branches.branch_code COLLATE utf8mb4_unicode_ci'));
        })
        ->join('user_documents', 'users.id', '=', 'user_documents.user_id')
        ->join('counties', 'users.county_id', '=', 'counties.id')
        ->join('roles', 'users.role_id', '=', 'roles.id');

    // Base columns 
    $baseColumns = [
        DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name"),
        'roles.display_name as role',
        DB::raw("CASE WHEN users.approved_for_payment = 1 THEN 'Yes' ELSE 'No' END as approved_for_payment"),
        DB::raw("CONCAT('254', SUBSTRING(users.phone, -9)) as phone_number"),
        'banks.name as bank_name',
        DB::raw("CASE 
            WHEN user_manual_bank_details.use_manual_details = 1 THEN user_manual_bank_details.manual_branch_name
            ELSE bank_branches.branch_name 
        END as branch_name"),
        DB::raw("CASE 
            WHEN user_manual_bank_details.use_manual_details = 1 THEN user_manual_bank_details.manual_branch_code
            ELSE user_bank_details.bank_branch
        END as branch_code"),
        DB::raw("UPPER(user_bank_details.account_name) as account_name")
    ];

    $query->select($baseColumns);
    
    $selectColumns = [];
    foreach ($columns as $column) {
        switch ($column) {
            case 'email':
                $selectColumns[] = 'users.email';
                break;
            case 'approved_for_payment': 
                    $selectColumns[] = DB::raw("CASE WHEN users.approved_for_payment = 1 THEN 'Yes' ELSE 'No' END as approved_for_payment");
                    break;
            case 'account_number':
                $selectColumns[] = 'user_bank_details.account_number';
                break;
            case 'id_number':
                $selectColumns[] = 'user_documents.id_number';
                break;
            case 'kra_pin':
                $selectColumns[] = 'user_documents.kra_pin';
                break;
            case 'cycle':
                $selectColumns[] = 'payment_cycles.description as cycle';
                break;
            case 'amount_payable':
            case 'tax':
            case 'productivity':
            case 'advance_pay':
            case 'net_payable':
                $selectColumns[] = DB::raw("CAST(payments.{$column} AS DECIMAL(10,2)) as {$column}");
                break;
            case 'status':
                $selectColumns[] = 'payments.status';
                break;
            case 'invoice_number':
                $selectColumns[] = DB::raw("CASE 
                    WHEN payments.status = 'Approved' 
                    THEN COALESCE(payments.invoice_number, payments.new_invoice_number)
                    ELSE NULL 
                END as invoice_number");
                break;
            case 'invoice_file':
                // Only show invoice file URL for Approved status
                $selectColumns[] = DB::raw("CASE 
                    WHEN payments.status = 'Approved' THEN 
                        CONCAT('" . asset('storage/') . "/', COALESCE(payments.invoice_file, payments.new_invoice_file))
                    ELSE NULL 
                END as invoice_file");
                break;
        }
    }

    if (!empty($selectColumns)) {
        $query->addSelect($selectColumns);
    }

    return $query;
}

private function getPaymentStatuses()
{
    return [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'approved' => 'Approved', 
        'rejected' => 'Rejected',
    ];
}

private function applySupportIssueFilters($query, $filter, $value)
{
    switch ($filter) {
        case 'status':
            $query->where('support_issues.status', $value);
            break;
        case 'priority':
            $query->where('support_issues.priority', $value);
            break;
        case 'category_id':
            $query->where('support_issues.category_id', $value);
            break;
        case 'county_id':
            $query->where('users.county_id', $value);
            break;
        case 'date_range':
            $dates = explode(' - ', $value);
            if (count($dates) === 2) {
                $query->whereBetween('support_issues.created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
            break;
        case 'search':
            $query->where('subject', 'like', "%{$value}%");
            break;
    }
}




private function buildUserQuery($columns)
{
    $query = User::query()
        ->leftJoin('counties', 'users.county_id', '=', 'counties.id')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('users as supervisors', 'users.supervisor_id', '=', 'supervisors.id')
        ->leftJoin('user_contract_signatures', 'users.id', '=', 'user_contract_signatures.user_id');

    $selectColumns = $this->getSelectColumns($columns, 'users');
    $query->select($selectColumns);

    return $query;
}
    
private function getSelectColumns($columns, $type)
{
    if ($type === 'users') {
        $selectColumns = [];
        $seenColumns = [];

        foreach ($columns as $column) {
            $columnName = is_string($column) ? $column : $column->getValue();
            
            if (isset($seenColumns[$columnName])) {
                continue;
            }

            $seenColumns[$columnName] = true;

            switch ($columnName) {
                case 'county_id':
                    $selectColumns[] = 'counties.name as county';
                    break;
                case 'role_id':
                    $selectColumns[] = 'roles.name as role';
                    break;
                case 'supervisor_id':
                    $selectColumns[] = DB::raw("CONCAT(COALESCE(supervisors.first_name, ''), ' ', COALESCE(supervisors.last_name, '')) as supervisor");
                    break;
                case 'contract_status':
                    $selectColumns[] = 'user_contract_signatures.status as contract_status';
                    break;
                case 'status':
                    $selectColumns[] = 'users.status';
                    break;
                default:
                    if (in_array($columnName, [
                        'id', 'email', 'username', 'first_name', 'last_name',
                        'phone', 'completed'
                    ])) {
                        $selectColumns[] = "users.{$columnName}";
                    }
                    break;
            }
        }

        return $selectColumns;
    }

    return $this->getSelectColumnsForOtherTypes($columns, $type);
}

private function getSelectColumnsForOtherTypes($columns, $type)
{
    $selectColumns = [];
    foreach ($columns as $column) {
        switch ($type) {
            case 'payroll':
                $column = $this->getPayrollColumnSelect($column);
                break;
            case 'training':
                $column = $this->getTrainingColumnSelect($column);
                break;
        }
        if ($column) {
            $selectColumns[] = $column;
        }
    }
    return array_unique($selectColumns);
}

    private function getColumnName($column)
    {
        if ($column instanceof \Illuminate\Database\Query\Expression) {
            return trim(preg_replace('/\s+as\s+[^\s]+$/i', '', $column->getValue()));
        }
        return (string)$column;
    }
    
    private function transformColumns($columns, $type)
    {
        $finalColumns = [];
        
        // If it's a master payroll report, return all columns including invoice columns
        if ($type === 'master_payroll') {
            return array_keys($this->getMasterPayrollColumns());
        }
        
        foreach ($columns as $column) {
            $columnName = $this->getColumnName($column);
            
            if ($type === 'training') {
                switch ($columnName) {
                    case 'name':
                        $finalColumns[] = 'attendee_name';
                        break;
                    case 'event_name':
                        $finalColumns[] = 'event_name';
                        break;
                    case 'county_name':
                        $finalColumns[] = 'county_name';
                        break;
                    default:
                        $finalColumns[] = $columnName;
                        break;
                }
            } else {
                switch ($columnName) {
                    case 'county_id':
                        $finalColumns[] = 'county';
                        break;
                    case 'role_id':
                        $finalColumns[] = 'role';
                        break;
                    case 'supervisor_id':
                        $finalColumns[] = 'supervisor';
                        break;
                    default:
                        $finalColumns[] = $columnName;
                        break;
                }
            }
        }
        
        return array_unique($finalColumns);
    }

    

private function getUserColumnSelect($columnName)
{
    switch ($columnName) {
        case 'county_id':
            return 'counties.name as county';
        case 'role_id':
            return 'roles.name as role';
        case 'supervisor_id':
            return DB::raw("CONCAT(COALESCE(supervisors.first_name, ''), ' ', COALESCE(supervisors.last_name, '')) as supervisor");
        case 'contract_status':
            return 'user_contract_signatures.status as contract_status';
        case 'status':
            return 'users.status';
        case 'id':
        case 'email':
        case 'username':
        case 'first_name':
        case 'last_name':
        case 'phone':
        case 'completed':
            return "users.{$columnName}";
        default:
            return null;
    }
}


private function getPayrollColumnSelect($columnName)
{
    switch ($columnName) {
        case 'user_name':
            return DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name");
        case 'email':
            return 'users.email';
        case 'phone_number':
            return 'users.phone as phone_number';
        case 'bank_name':
            return 'banks.name as bank_name';
        case 'bank_branch':
            return 'user_bank_details.bank_branch';
        case 'account_name':
            return 'user_bank_details.account_name';
        case 'account_number':
            return 'user_bank_details.account_number';
        case 'id_number':
            return 'user_documents.id_number';
        case 'kra_pin':
            return 'user_documents.kra_pin';
        case 'cycle':
            return 'payment_cycles.description as cycle';
        case 'amount_payable':
        case 'tax':
        case 'productivity':
        case 'status':
        case 'invoice_number':
            return "payments.{$columnName}";
        default:
            return null;
    }
}

private function getTrainingColumnSelect($columnName)
{
    switch ($columnName) {
        case 'event_name':
            return 'training_events.name as event_name';
        case 'county_name':
            return 'counties.name as county_name';
        case 'phone_verified':
            return DB::raw('CASE WHEN event_attendances.phone_verified = 1 THEN "Verified" ELSE "Not Verified" END as phone_verified');
        case 'completed':
            return DB::raw('CASE WHEN event_attendances.completed = 1 THEN "Completed" ELSE "Incomplete" END as completed');
        case 'total_amount':
            return DB::raw('CAST(event_attendances.total_amount AS DECIMAL(10,2)) as total_amount');
        default:
            if (in_array($columnName, [
                'name', 'id_number', 'phone_number', 'email', 'designation', 
                'days_attended', 'created_at'
            ])) {
                return "event_attendances.{$columnName}";
            }
            return null;
    }
}

private function buildMasterPayrollQuery($columns)
{
    // Create a subquery to get numbered payments for each user
    $subquery = DB::table('payments as p1')
        ->select(
            'p1.user_id',
            'p1.id as payment_id',
            DB::raw('(
                SELECT COUNT(*) 
                FROM payments as p2 
                WHERE p2.user_id = p1.user_id AND 
                (p2.created_at < p1.created_at OR 
                (p2.created_at = p1.created_at AND p2.id <= p1.id))
            ) as invoice_sequence'),
            'p1.invoice_number',
            'p1.new_invoice_number',
            'p1.invoice_file',
            'p1.new_invoice_file',
            'p1.status',
            'p1.amount_payable',
            'p1.tax',
            'p1.advance_pay',
            'p1.net_payable',
            'p1.productivity',
            'p1.created_at',
            DB::raw('COALESCE(pc.description, "") as cycle_description')
        )
        ->leftJoin('payment_cycles as pc', 'p1.payment_cycle_id', '=', 'pc.id')
        ->orderBy('p1.created_at');

    // Main query
    $query = Payment::query()
        ->select([
            // User details
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name"),
            'users.email',
            'roles.display_name as role',
            DB::raw("CASE WHEN users.approved_for_payment = 1 THEN 'Yes' ELSE 'No' END as approved_for_payment"),
            DB::raw("CONCAT('254', SUBSTRING(users.phone, -9)) as phone_number"),
            
            // Bank details
            'banks.name as bank_name',
            DB::raw("CASE WHEN user_manual_bank_details.use_manual_details = 1 
                         THEN user_manual_bank_details.manual_branch_name 
                         ELSE bank_branches.branch_name END as branch_name"),
            DB::raw("CASE WHEN user_manual_bank_details.use_manual_details = 1 
                         THEN user_manual_bank_details.manual_branch_code 
                         ELSE user_bank_details.bank_branch END as branch_code"),
            DB::raw("UPPER(user_bank_details.account_name) as account_name"),
            'user_bank_details.account_number',
            
            // Identification
            'user_documents.id_number',
            'user_documents.kra_pin',
            'counties.name as county',
            
            // Totals and aggregates
            DB::raw('(SUM(payments.net_payable) + SUM(payments.tax)) as total_amount_payable'),
            DB::raw('SUM(payments.tax) as total_tax'),
            DB::raw('SUM(payments.advance_pay) as total_advance_pay'),
            DB::raw('SUM(payments.net_payable) as total_net_payable'),
            
            // Get all payments data as JSON to parse later, showing invoice files if they exist
            DB::raw('JSON_ARRAYAGG(
                JSON_OBJECT(
                    "sequence", numbered_payments.invoice_sequence,
                    "invoice_number", COALESCE(payments.invoice_number, payments.new_invoice_number),
                    "invoice_file", CASE 
                        WHEN payments.invoice_file IS NOT NULL THEN payments.invoice_file
                        WHEN payments.new_invoice_file IS NOT NULL THEN payments.new_invoice_file
                        ELSE NULL 
                    END,
                    "invoice_type", CASE 
                        WHEN payments.invoice_file IS NOT NULL THEN "invoice_file"
                        WHEN payments.new_invoice_file IS NOT NULL THEN "new_invoice_file"
                        ELSE NULL 
                    END,
                    "status", payments.status,
                    "amount_payable", payments.amount_payable,
                    "tax", payments.tax,
                    "advance_pay", payments.advance_pay,
                    "net_payable", payments.net_payable,
                    "productivity", payments.productivity,
                    "cycle", numbered_payments.cycle_description,
                    "date", DATE_FORMAT(payments.created_at, "%Y-%m-%d")
                )
            ) as payment_details')
        ])
        ->join('users', 'payments.user_id', '=', 'users.id')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->join('user_bank_details', 'users.id', '=', 'user_bank_details.user_id')
        ->join('banks', 'user_bank_details.bank_id', '=', 'banks.id')
        ->join('counties', 'users.county_id', '=', 'counties.id')
        ->join('user_documents', 'users.id', '=', 'user_documents.user_id')
        ->leftJoin('user_manual_bank_details', 'users.id', '=', 'user_manual_bank_details.user_id')
        ->leftJoin('bank_branches', function($join) {
            $join->on('user_bank_details.bank_id', '=', 'bank_branches.bank_id')
                 ->on(DB::raw('BINARY user_bank_details.bank_branch COLLATE utf8mb4_unicode_ci'), 
                     '=', 
                     DB::raw('BINARY bank_branches.branch_code COLLATE utf8mb4_unicode_ci'));
        })
        ->joinSub($subquery, 'numbered_payments', function($join) {
            $join->on('payments.id', '=', 'numbered_payments.payment_id');
        })
        ->groupBy(
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.phone',
            'users.email',
            'banks.name',
            'user_bank_details.account_name',
            'user_bank_details.account_number',
            'user_documents.id_number',
            'user_documents.kra_pin',
            'counties.name',
            DB::raw("CASE WHEN user_manual_bank_details.use_manual_details = 1 
                         THEN user_manual_bank_details.manual_branch_name 
                         ELSE bank_branches.branch_name END"),
            DB::raw("CASE WHEN user_manual_bank_details.use_manual_details = 1 
                         THEN user_manual_bank_details.manual_branch_code 
                         ELSE user_bank_details.bank_branch END")
        );

    return $query;
}

private function formatMasterPayrollData($data)
{
    foreach ($data as &$row) {
        if ($row->payment_details) {
            $payments = json_decode($row->payment_details);
            
            // Sort payments by sequence
            usort($payments, function($a, $b) {
                return $a->sequence <=> $b->sequence;
            });
            
            // Add separate columns for each invoice's details
            foreach ($payments as $index => $payment) {
                $prefix = 'invoice_' . ($index + 1);
                $row->{$prefix . '_number'} = $payment->invoice_number;
                
                // Include invoice file URL if a file exists, regardless of status
                if ($payment->invoice_file) {
                    $row->{$prefix . '_file'} = asset('storage/' . $payment->invoice_file);
                    $row->{$prefix . '_file_type'} = $payment->invoice_type;
                } else {
                    $row->{$prefix . '_file'} = null;
                    $row->{$prefix . '_file_type'} = null;
                }
                
                $row->{$prefix . '_status'} = $payment->status;
                $row->{$prefix . '_amount'} = $payment->amount_payable;
                $row->{$prefix . '_tax'} = $payment->tax;
                $row->{$prefix . '_advance'} = $payment->advance_pay;
                $row->{$prefix . '_net'} = $payment->net_payable;
                $row->{$prefix . '_productivity'} = $payment->productivity;
                $row->{$prefix . '_date'} = $payment->date;
                $row->{$prefix . '_cycle'} = $payment->cycle;
            }
        }
        
        unset($row->payment_details);
    }
    
    return $data;
}

public function getMasterPayrollColumns()
{
    $columns = [
        'user_name' => 'User Name',
        'email' => 'Email',
        'role' => 'Role',
        'approved_for_payment' => 'Approved for Payment',
        'phone_number' => 'Phone Number',
        'bank_name' => 'Bank Name',
        'branch_name' => 'Branch Name',
        'branch_code' => 'Branch Code',
        'account_name' => 'Account Name',
        'account_number' => 'Account Number',
        'id_number' => 'ID Number',
        'kra_pin' => 'KRA PIN',
        'county' => 'County',
        'total_amount_payable' => 'Total Amount',
        'total_tax' => 'Total Tax',
        'total_advance_pay' => 'Total Advance',
        'total_net_payable' => 'Total Net Pay',
        
    ];
    
    // Add columns for first 10 invoices (you can adjust this number)
    for ($i = 1; $i <= 10; $i++) {
        $prefix = 'invoice_' . $i;
        $columns[$prefix . '_number'] = "Invoice {$i} Number";
        $columns[$prefix . '_file'] = "Invoice {$i} File";
        $columns[$prefix . '_status'] = "Invoice {$i} Status";
        $columns[$prefix . '_amount'] = "Invoice {$i} Amount";
        $columns[$prefix . '_tax'] = "Invoice {$i} Tax";
        $columns[$prefix . '_advance'] = "Invoice {$i} Advance";
        $columns[$prefix . '_net'] = "Invoice {$i} Net Pay";
        $columns[$prefix . '_productivity'] = "Invoice {$i} Productivity";
        $columns[$prefix . '_date'] = "Invoice {$i} Date";
        $columns[$prefix . '_cycle'] = "Invoice {$i} Cycle";
    }
    
    return $columns;
}
    
    private function getAssetColumns()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sku_code' => 'SKU Code',
            'category' => 'Category',
            'quantity' => 'Total Quantity'
        ];
    }

    private function getSupportIssueColumns()
    {
        return [
            'reference_number' => 'Reference Number',
            'subject' => 'Subject',
            'category' => 'Category',
            'priority' => 'Priority',
            'status' => 'Status',
            'created_by' => 'Created By',
            'user_role' => 'User Role',
            'county' => 'County',
            'escalated_by' => 'Escalated By',
            'escalated_at' => 'Escalation Date',
            'created_at' => 'Created Date',
            'updated_at' => 'Last Updated',
            'resolution_time' => 'Resolution Time (Hours)'
        ];
    }

    private function getIssueStatuses()
{
    return [
        'Pending' => 'Pending',
        'Open' => 'Open',
        'Closed' => 'Closed',
        'Escalated' => 'Escalated'
    ];
}

private function getIssuePriorities()
{
    return [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High'
    ];
}

    

    private function buildAssetQuery($columns)
    {
        $query = Asset::query();
        $validColumns = ['id', 'name', 'sku_code', 'category', 'quantity'];
        $columnsToSelect = array_intersect($columns, $validColumns);
        $query->select($columnsToSelect);
        return $query;
    }


    private function buildAssetAssignmentQuery($columns)
    {
        $query = DB::table('asset_assignments')  
            ->join('assets', 'asset_assignments.asset_id', '=', 'assets.id')
            ->join('users as assigned_by_user', 'asset_assignments.assigned_by', '=', 'assigned_by_user.id')
            ->join('users as assigned_to_user', 'asset_assignments.assigned_to', '=', 'assigned_to_user.id')
            ->join('roles', 'assigned_to_user.role_id', '=', 'roles.id')
            ->leftJoin('counties', 'assigned_to_user.county_id', '=', 'counties.id');
    
        $selectColumns = [];
        foreach ($columns as $column) {
            switch ($column) {
                case 'asset_name':
                    $selectColumns[] = 'assets.name as asset_name';
                    break;
                case 'assigned_by':
                    $selectColumns[] = DB::raw("CONCAT(assigned_by_user.first_name, ' ', assigned_by_user.last_name) as assigned_by");
                    break;
                case 'assigned_to':
                    $selectColumns[] = DB::raw("CONCAT(assigned_to_user.first_name, ' ', assigned_to_user.last_name) as assigned_to");
                    break;
                case 'assigned_to_role':
                    $selectColumns[] = 'roles.name as assigned_to_role';
                    break;
                case 'assigned_to_county':
                    $selectColumns[] = 'counties.name as assigned_to_county';
                    break;
                case 'physical_condition':
                    $selectColumns[] = 'asset_assignments.physical_condition';
                    break;
                case 'assignment_status':
                    $selectColumns[] = 'asset_assignments.assignment_status';
                    break;
                case 'imei_number':
                case 'serial_number':
                case 'comments':
                case 'created_at':
                    $selectColumns[] = "asset_assignments.$column";
                    break;
            }
        }
        $query->select($selectColumns);
    
        return $query;
    }

    private function buildSupportIssueQuery($columns)
{
    if (empty($columns)) {
        $columns = ['reference_number', 'subject', 'category', 'status'];
    }

    $baseQuery = SupportIssue::query()
        ->join('users', 'support_issues.user_id', '=', 'users.id')
        ->join('issues_categories', 'support_issues.category_id', '=', 'issues_categories.id')
        ->leftJoin('counties', 'users.county_id', '=', 'counties.id')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id');

    // Only add escalation join if column exists
    if (Schema::hasColumn('support_issues', 'escalated_by')) {
        $baseQuery->leftJoin('users as escalated_by_user', 'support_issues.escalated_by', '=', 'escalated_by_user.id');
    }

    $selectColumns = [];
    foreach ($columns as $column) {
        switch ($column) {
            case 'reference_number':
                $selectColumns[] = 'support_issues.id as reference_number';
                break;
            case 'subject':
                $selectColumns[] = 'support_issues.subject';
                break;
            case 'category':
                $selectColumns[] = 'issues_categories.name as category';
                break;
            case 'priority':
                $selectColumns[] = DB::raw("CASE 
                    WHEN support_issues.priority = 'high' THEN 'High'
                    WHEN support_issues.priority = 'medium' THEN 'Medium'
                    WHEN support_issues.priority = 'low' THEN 'Low'
                    ELSE support_issues.priority
                END as priority");
                break;
            case 'status':
                $selectColumns[] = 'support_issues.status';
                break;
            case 'created_by':
                $selectColumns[] = DB::raw("CONCAT(users.first_name, ' ', users.last_name) as created_by");
                break;
            case 'user_role':
                $selectColumns[] = 'roles.name as user_role';
                break;
            case 'county':
                $selectColumns[] = 'counties.name as county';
                break;
            case 'escalated_by':
                if (Schema::hasColumn('support_issues', 'escalated_by')) {
                    $selectColumns[] = DB::raw("CASE 
                        WHEN support_issues.escalated_by IS NOT NULL 
                        THEN CONCAT(escalated_by_user.first_name, ' ', escalated_by_user.last_name)
                        ELSE NULL 
                    END as escalated_by");
                } else {
                    $selectColumns[] = DB::raw("NULL as escalated_by");
                }
                break;
            case 'escalated_at':
                if (Schema::hasColumn('support_issues', 'escalated_at')) {
                    $selectColumns[] = DB::raw('DATE_FORMAT(support_issues.escalated_at, "%Y-%m-%d %H:%i:%s") as escalated_at');
                } else {
                    $selectColumns[] = DB::raw("NULL as escalated_at");
                }
                break;
            case 'created_at':
                $selectColumns[] = DB::raw('DATE_FORMAT(support_issues.created_at, "%Y-%m-%d %H:%i:%s") as created_at');
                break;
            case 'updated_at':
                $selectColumns[] = DB::raw('DATE_FORMAT(support_issues.updated_at, "%Y-%m-%d %H:%i:%s") as updated_at');
                break;
            case 'resolution_time':
                $selectColumns[] = DB::raw('
                    CASE 
                        WHEN support_issues.status = "Closed" 
                        THEN TIMESTAMPDIFF(HOUR, support_issues.created_at, support_issues.updated_at)
                        ELSE NULL 
                    END as resolution_time
                ');
                break;
        }
    }

    if (empty($selectColumns)) {
        $selectColumns[] = 'support_issues.id as reference_number';
    }

    return $baseQuery->select($selectColumns);
}

    private function applyFilters($query, $type, $filters)
    {
        foreach ($filters as $filter => $value) {
            if ($value !== null && $value !== '') {
                switch ($type) {
                    case 'users':
                        $this->applyUserFilters($query, $filter, $value);
                        break;
                    case 'assets':
                        $query->where($filter, $value);
                        break;
                    case 'asset_assignments':
                        $this->applyAssetAssignmentFilters($query, $filter, $value);
                        break;
                    case 'payroll':
                        $this->applyPayrollFilters($query, $filter, $value);
                        break;
                    case 'master_payroll':
                        $this->applyMasterPayrollFilters($query, $filter, $value);
                        break;
                    case 'training':
                        $this->applyTrainingFilters($query, $filter, $value);
                        break;
                    case 'contracts':
                            $this->applyContractFilters($query, $filter, $value); 
                            break;
                    case 'support_issues':
                            $this->applySupportIssueFilters($query, $filter, $value);
                            break;
                }
            }
        }
    }

    
    private function applyTrainingFilters($query, $filter, $value)
    {
        switch ($filter) {
            case 'county_id':
                $query->where('training_events.county_id', $value);
                break;
            case 'completed':
                $query->where('event_attendances.completed', $value === 'completed');
                break;
            case 'phone_verified':
                $query->where('event_attendances.phone_verified', $value === 'verified');
                break;
            case 'date_range':
                $dates = explode(' - ', $value);
                if (count($dates) === 2) {
                    $query->whereBetween('event_attendances.created_at', [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay()
                    ]);
                }
                break;
            case 'training_status':
                $now = now();
                switch ($value) {
                    case 'completed':
                        $query->where('training_events.end_date', '<', $now);
                        break;
                    case 'ongoing':
                        $query->where('training_events.start_date', '<=', $now)
                              ->where('training_events.end_date', '>=', $now);
                        break;
                    case 'pending':
                        $query->where('training_events.start_date', '>', $now);
                        break;
                }
                break;
            default:
                // Handle text search across multiple fields
                $query->where(function ($q) use ($value) {
                    $q->where('event_attendances.name', 'LIKE', "%{$value}%")
                      ->orWhere('event_attendances.id_number', 'LIKE', "%{$value}%")
                      ->orWhere('event_attendances.phone_number', 'LIKE', "%{$value}%")
                      ->orWhere('event_attendances.email', 'LIKE', "%{$value}%")
                      ->orWhere('training_events.name', 'LIKE', "%{$value}%");
                });
        }
    }

    private function applyUserFilters($query, $filter, $value)
    {
        switch ($filter) {
            case 'county_id':
                $query->where('users.county_id', $value);
                break;
            case 'role_id':
                $query->where('users.role_id', $value);
                break;
            case 'status':
                $query->where('users.status', $value);
                break;
            case 'contract_status':
                $query->where('user_contract_signatures.status', $value);
                break;
            case 'completed':
                $query->where('users.completed', $value === '1' ? true : false);
                break;
            default:
                $query->where("users.$filter", 'LIKE', "%$value%");
        }
    }


    private function applyPayrollFilters($query, $filter, $value)
    {
        switch ($filter) {
            case 'cycle':
                $query->where('payments.payment_cycle_id', $value);
                break;
            case 'county':
                $query->where('users.county_id', $value);
                break;
            case 'status':
                $query->where('payments.status', $value);
                break;
            case 'role':
                $query->where('users.role_id', $value);
                break;
            default:
                $query->where("payments.$filter", 'LIKE', "%$value%");
        }
    }

    private function applyMasterPayrollFilters($query, $filter, $value)
    {
        switch ($filter) {
            case 'status':
                if ($value !== 'Approved' && $value !== 'Rejected') {
                    $query->where('payments.status', $value)
                          ->whereNotIn('payments.status', ['Approved', 'Rejected']);
                }
                break;
                
            case 'approval_status':
                if ($value === 'Approved' || $value === 'Rejected') {
                    $query->where('payments.status', $value);
                }
                break;
    
            case 'cycle':
                $query->where('payments.payment_cycle_id', $value);
                break;
    
            case 'county':
                $query->where('users.county_id', $value);
                break;
    
            case 'date_range':
                $dates = explode(' - ', $value);
                if (count($dates) === 2) {
                    $query->whereBetween('payments.created_at', [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay()
                    ]);
                }
                break;
    
            default:
                $query->where("payments.$filter", 'LIKE', "%$value%");
        }
    }
 
    private function getUserColumns()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'county_id' => 'County',
            'role_id' => 'Role',
            'supervisor_id' => 'Supervisor',
            'contract_status' => 'Contract Status',
            'completed' => 'Completed',
        ];
    }


    private function getTrainingColumns()
    {
        return [
            'name' => 'Attendee Name',
            'id_number' => 'ID Number',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'designation' => 'Designation',
            'days_attended' => 'Days Attended',
            'total_amount' => 'Total Amount',
            'completed' => 'Completion Status',
            'phone_verified' => 'Phone Verified',
            'event_name' => 'Training Event',
            'venue_name' => 'Venue',
            'county_name' => 'County',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'daily_amount' => 'Daily Amount',
            'created_at' => 'Registration Date'
        ];
    }

    private function getTrainingStatuses()
    {
        return [
            'completed' => 'Completed',
            'ongoing' => 'Ongoing',
            'pending' => 'Pending'
        ];
    }

    public function getMasterPayrollStatuses()
{
    return [
        'status' => [
            'Pending' => 'Pending',
            'Processing' => 'Processing',
            'Invoice Uploaded' => 'Invoice Uploaded'
        ],
        'approval_status' => [
            'Approved' => 'Approved',
            'Rejected' => 'Rejected'
        ]
    ];
}



private function getPayrollColumns()
{
    return [
        'user_name' => 'User Name',
        'email' => 'Email',
        'role' => 'Role',
        'approved_for_payment' => 'Approved for Payment',
        'phone_number' => 'Phone Number',
        'bank_name' => 'Bank Name',
        'branch_name' => 'Branch Name',
        'branch_code' => 'Branch Code',
        'account_name' => 'Account Name',
        'account_number' => 'Account Number',
        'id_number' => 'ID Number',
        'kra_pin' => 'KRA PIN',
        'amount_payable' => 'Amount Payable',
        'tax' => 'Tax',
        'advance_pay' => 'Advance Pay',
        'net_payable' => 'Net Payable',
        'productivity' => 'Productivity',
        'status' => 'Status',
        'invoice_number' => 'Invoice Number',
        'invoice_file' => 'Invoice',
        'cycle' => 'Payment Cycle',
    ];
}


    private function getAssetAssignmentColumns()
    {
        return [
            'asset_name' => 'Asset Name',
            'assigned_by' => 'Assigned By',
            'assigned_to' => 'Assigned To',
            'assigned_to_role' => 'Assigned To Role',
            'assigned_to_county' => 'Assigned To County',
            'imei_number' => 'IMEI Number',
            'serial_number' => 'Serial Number',
            'physical_condition' => 'Physical Condition',
            'assignment_status' => 'Assignment Status',
            'created_at' => 'Assignment Date',
        ];
    }



    private function getContractStatuses()
    {
        return [
            'draft' => 'Draft',
            'approved' => 'Approved',
            'accepted' => 'Accepted',
            'declined' => 'Declined',
        ];
    }

    private function getCompletedStatuses()
    {
        return [
            0 => 'Incomplete',
            1 => 'Complete',
        ];
    }
}