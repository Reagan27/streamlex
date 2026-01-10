<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Appraisal;
use Vanguard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Vanguard\AdminContract;
use Vanguard\Services\RoleHierarchyService;
use Exception;
use Illuminate\Support\Facades\Log;
use Vanguard\Mail\RecommendationCertificateMail;
use Vanguard\Mail\RecommendationRejection;
use Vanguard\RecommendationCertificate;
use Vanguard\Services\RecommendationCertificatePDF;

class AppraisalController extends Controller
{
    protected $roleHierarchyService;

    public function __construct(RoleHierarchyService $roleHierarchyService)
    {
        $this->middleware('auth');
        $this->roleHierarchyService = $roleHierarchyService;
    }
    
 public function index(Request $request)
{
    $currentUser = auth()->user();

    $query = User::query();

    // Only users with accepted contracts
    $query->whereHas('contractSignatures', function ($q) {
        $q->where('status', 'accepted');
    });

    // Search filter
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%")
              ->orWhere('email', 'like', "%{$searchTerm}%");
        });
    }

    // GLOBAL PROJECT FILTER (from navbar dropdown)
    if ($request->filled('project_id')) {
        $projectId = (int) $request->project_id;

        // Only apply for roles that can see multiple projects
        if (
            $currentUser->isAdmin() ||
            $currentUser->hasRole('Manager') ||
            $currentUser->hasRole('Finance') ||
            in_array($currentUser->role->name, ['Regional_Coordinator', 'County_Coordinator'])
        ) {
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId)
                  ->where('projects_user.is_active_project', true);
            });
        }
    }

    // Role-based visibility (your existing logic)
    if ($currentUser->hasRole('Admin')) {
        // Admin sees all
        $query->with(['appraisals', 'county', 'role']);

    } elseif ($currentUser->role->name === 'Regional_Coordinator') {
        $subordinateRoles = $this->roleHierarchyService->getAllSubordinateRoles($currentUser->role->name);

        $query->whereHas('role', function ($q) use ($subordinateRoles) {
            $q->whereIn('name', $subordinateRoles);
        });

        $assignedCountyIds = $currentUser->counties()->pluck('counties.id');

        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        } else {
            $query->whereIn('county_id', $assignedCountyIds);
        }

        $query->with(['appraisals', 'county', 'role']);

    } elseif ($currentUser->role->name === 'County_Coordinator') {
        $subordinateRoles = $this->roleHierarchyService->getAllSubordinateRoles($currentUser->role->name);

        $query->whereHas('role', function ($q) use ($subordinateRoles) {
            $q->whereIn('name', $subordinateRoles);
        })->where('county_id', $currentUser->county_id);

        $query->with(['appraisals', 'county', 'role']);

    } elseif ($currentUser->role->name === 'Supervisor') {
        $query->where('supervisor_id', $currentUser->id)
              ->whereHas('role', function ($q) {
                  $q->where('name', 'Field_Officer');
              });

        $query->with(['appraisals', 'county', 'role']);

    } else {
        // Fallback: only themselves
        $query->where('id', $currentUser->id)
              ->with(['appraisals', 'county', 'role']);
    }

    $users = $query->paginate(20);

    // Counties dropdown for Regional Coordinators
    $assignedCounties = $currentUser->role->name === 'Regional_Coordinator'
        ? $currentUser->counties()->pluck('name', 'counties.id')
        : collect();

    return view('appraisal.index', compact('users', 'assignedCounties'));
}

    public function create(User $user)
    {
        $contract = AdminContract::where('role_id', $user->role_id)
                                 ->where('status', 'published')
                                 ->orderBy('start_date', 'desc')
                                 ->first();

        if (!$contract) {
            return redirect()->route('appraisals.index')->with('error', 'No active contract found for this user.');
        }

        $startDate = Carbon::parse($contract->start_date);
        $now = Carbon::now();
        $daysSinceStart = $startDate->diffInDays($now);
        $weekNumber = floor($daysSinceStart / 7) + 1;

        $period = (string)$weekNumber; 
        $appraisalDate = $now->format('Y-m-d');
    
        return view('appraisal.create', compact('user', 'period', 'appraisalDate', 'weekNumber', 'startDate'));
    }

    public function store(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'period' => 'required|string',
            'motivation_score' => 'required|integer|min:1|max:5',
            'resourcefulness_score' => 'required|integer|min:1|max:5',
            'leadership_score' => 'required|integer|min:1|max:5',
            'discipline_score' => 'required|integer|min:1|max:5',
            'teamwork_score' => 'required|integer|min:1|max:5',
            'comments' => 'required|string',
        ]);

        $appraisal = new Appraisal($validatedData);
        $appraisal->user_id = $user->id;
        $appraisal->appraiser_id = auth()->id();
        $appraisal->appraisal_date = Carbon::now();
        $appraisal->status = true;
        $appraisal->save();

        return redirect()->route('appraisals.index')->with('success', 'Appraisal created successfully.');
    }

    public function show(Appraisal $appraisal)
    {
        return view('appraisal.show', compact('appraisal'));
    }

    public function edit(Appraisal $appraisal)
    {
        $contract = AdminContract::where('role_id', $appraisal->user->role_id)
                                 ->where('status', 'published')
                                 ->orderBy('start_date', 'desc')
                                 ->first();

        if (!$contract) {
            return redirect()->route('appraisals.index')->with('error', 'No active contract found for this user.');
        }

        $startDate = Carbon::parse($contract->start_date);
        $now = Carbon::now();
        $daysSinceStart = $startDate->diffInDays($now);
        $weekNumber = floor($daysSinceStart / 7) + 1;

        $period = "{$weekNumber}";
        $appraisalDate = $now->format('Y-m-d');

        return view('appraisal.edit', compact('appraisal', 'period', 'appraisalDate', 'weekNumber'));
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        $validatedData = $request->validate([
            'motivation_score' => 'required|integer|min:1|max:5',
            'resourcefulness_score' => 'required|integer|min:1|max:5',
            'leadership_score' => 'required|integer|min:1|max:5',
            'discipline_score' => 'required|integer|min:1|max:5',
            'teamwork_score' => 'required|integer|min:1|max:5',
            'comments' => 'required|string',
        ]);

        $contract = AdminContract::where('role_id', $appraisal->user->role_id)
                                 ->where('status', 'published')
                                 ->orderBy('start_date', 'desc')
                                 ->first();

        if (!$contract) {
            return redirect()->route('appraisals.index')->with('error', 'No active contract found for this user.');
        }

        $startDate = Carbon::parse($contract->start_date);
        $now = Carbon::now();
        $daysSinceStart = $startDate->diffInDays($now);
        $weekNumber = floor($daysSinceStart / 7) + 1;

        $period = (string)$weekNumber; 
        $appraisalDate = $now->format('Y-m-d');
    
        $appraisal->update(array_merge($validatedData, [
            'period' => $period,
            'appraisal_date' => $appraisalDate
        ]));
    
        return redirect()->route('appraisals.index')->with('success', 'Appraisal updated successfully.');
    }


    public function complete(Appraisal $appraisal)
{
    $appraisal->markAsCompleted();

    return redirect()->route('appraisals.index')->with('success', 'Appraisal marked as completed.');
}
public function history(User $user)
{
    $appraisals = $user->appraisals()
        ->orderBy('appraisal_date', 'desc')
        ->paginate(10);

    // Calculate overall average scores for each appraisal
    foreach ($appraisals as $appraisal) {
        $appraisal->overall_rating = collect([
            $appraisal->motivation_score,
            $appraisal->resourcefulness_score,
            $appraisal->leadership_score,
            $appraisal->discipline_score,
            $appraisal->teamwork_score,
        ])->filter()->average();
    }

    $supervisor = $this->getSupervisor($user);
    return view('appraisal.history', compact('user', 'appraisals', 'supervisor'));
}

private function getSupervisor(User $user)
{
    switch ($user->role->name) {
        case 'Regional_Coordinator':
            return User::whereIn('role_id', function($query) {
                $query->select('id')->from('roles')->whereIn('name', ['Admin', 'Manager']);
            })->first();
        case 'County_Coordinator':
            return User::where('role_id', function($query) {
                $query->select('id')->from('roles')->where('name', 'Regional_Coordinator');
            })
            ->whereHas('counties', function($query) use ($user) {
                $query->where('counties.id', $user->county_id);
            })
            ->first();
        case 'Supervisor':
            return User::where('role_id', function($query) {
                $query->select('id')->from('roles')->where('name', 'County_Coordinator');
            })
            ->where('county_id', $user->county_id)
            ->first();
        case 'Field_Officer':
            return $user->supervisor;
        default:
            return null;
    }
}

public function generate(Request $request, User $user)
    {
        try {
            $type = $request->input('type');
            $email = $request->input('email', $user->email);
            $appraisals = $user->appraisals()->where('status', true)->get();
            
            if ($type === 'recommendation') {
                if ($appraisals->isEmpty()) {
                    return response()->json([
                        'message' => 'No completed appraisals found for this user.',
                        'success' => false,
                        'meetsRequirements' => false
                    ], 400);
                }

                $overallRating = $appraisals->avg(function ($appraisal) {
                    return ($appraisal->motivation_score + $appraisal->resourcefulness_score +
                            $appraisal->leadership_score + $appraisal->discipline_score +
                            $appraisal->teamwork_score) / 5;
                });
                
                $overallPercentage = ($overallRating / 5) * 100;
                
                $template = $this->getRecommendationTemplate($overallPercentage);
                
                if (!$template) {
                    return response()->json([
                        'message' => 'No suitable recommendation template found.',
                        'success' => false,
                        'meetsRequirements' => false,
                        'overallRating' => number_format($overallRating, 2),
                        'overallPercentage' => number_format($overallPercentage, 2),
                        'email' => $user->email,
                    ]);
                }
                
                $meetsRequirements = $template->threshold_status ? ($overallPercentage >= $template->threshold) : true;
            } else {
                // For certificate of service
                $template = RecommendationCertificate::where('type', 'certificate')->first();
                if (!$template) {
                    return response()->json([
                        'message' => 'No certificate of service template found.',
                        'success' => false,
                        'meetsRequirements' => false
                    ]);
                }
                $meetsRequirements = true; // Always true for certificate of service
                $overallRating = null;
                $overallPercentage = null;
            }

            if ($request->input('send')) {
                return $this->sendDocument($user, $type, $meetsRequirements, $overallRating, $overallPercentage, $template, $email);
            }

            $supervisor = $this->getSupervisor($user);

            return response()->json([
                'meetsRequirements' => $meetsRequirements,
                'overallRating' => $overallRating ? number_format($overallRating, 2) : null,
                'overallPercentage' => $overallPercentage ? number_format($overallPercentage, 2) : null,
                'email' => $email,
                'supervisor' => $supervisor ? [
                    'first_name' => $supervisor->first_name,
                    'last_name' => $supervisor->last_name,
                    'email' => $supervisor->email,
                    'phone' => $supervisor->phone
                ] : null
            ]);
            
        } catch (Exception $e) {
            Log::error('Generation Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'An error occurred while generating the ' . $type . ': ' . $e->getMessage(),
                'success' => false,
                'meetsRequirements' => false
            ], 500);
        }
    }

    private function getRecommendationTemplate($overallPercentage)
    {
        $template = RecommendationCertificate::where('type', 'recommendation')
            ->where('threshold_status', true)
            ->where('threshold', '<=', $overallPercentage)
            ->orderByDesc('threshold')
            ->first();
        
        if (!$template) {
            $template = RecommendationCertificate::where('type', 'recommendation')
                ->where('threshold_status', false)
                ->first();
        }

        return $template;
    }

    private function sendDocument($user, $type, $meetsRequirements, $overallRating, $overallPercentage, $template, $email)
    {
        Log::info('Sending document', [
            'user_id' => $user->id,
            'type' => $type,
            'meetsRequirements' => $meetsRequirements,
            'email' => $email
        ]);
    
        if ($meetsRequirements) {
            $pdf = new RecommendationCertificatePDF();
            $pdfContent = $pdf->generate($user, $type, $overallRating, $overallPercentage, $template);
            
            Log::info('Sending email', [
                'email' => $email,
                'type' => $type
            ]);
    
            Mail::to($email)
                ->send(new RecommendationCertificateMail($user, $type, $overallRating, $overallPercentage, $pdfContent));
            
            Log::info('Email sent');
    
            return response()->json(['message' => ucfirst($type) . ' has been generated and sent.', 'success' => true]);
        } else {
            Log::info('Sending rejection email', [
                'email' => $email
            ]);
    
            Mail::to($email)
                ->send(new RecommendationRejection($user, $overallRating, $overallPercentage));
            
            Log::info('Rejection email sent');
    
            return response()->json(['message' => 'User does not meet the requirements. A feedback email has been sent.', 'success' => false]);
        }
    }
}