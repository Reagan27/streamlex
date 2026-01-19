<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\TrainingEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vanguard\EventAttendance;
use Vanguard\Exports\TrainingAttendancesExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Vanguard\BannedAttendee;
use Vanguard\Message;

class TrainingEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.view', ['only' => ['index', 'show', 'export', 'exportExcel']]);
        $this->middleware('permission:training.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:training.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:training.ban', ['only' => ['banAttendee', 'unbanAttendee']]);
    }

    public function index(Request $request)
    {
        $query = TrainingEvent::with(['attendances', 'county'])
            ->withCount(['attendances as attendances_count' => function($query) {
                $query->select(DB::raw('count(distinct id_number)'));
            }]);
    
        $user = auth()->user();
        $userRole = $user->role;
    
        if ($userRole->name === 'Regional_Coordinator') {
            $assignedCountyIds = DB::table('regional_coordinator_counties')
                ->where('user_id', $user->id)
                ->pluck('county_id');
    
            $query->whereIn('county_id', $assignedCountyIds);
        }
        elseif ($userRole->name === 'County_Coordinator') {
            $query->where('county_id', $user->county_id);
        }
    
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
    
        $events = $query->latest()->paginate(20);
    
        $canCreateEvent = $user->hasPermission('training.create');
        $canEditEvent = $user->hasPermission('training.edit');
    
        return view('training.index', compact('events', 'canCreateEvent', 'canEditEvent'));
    }

    public function create()
{
    $counties = \DB::table('counties')->orderBy('name')->pluck('name', 'id');
    return view('training.create', compact('counties'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'venue_name' => 'required|string|max:255',
        'county_id' => 'required|exists:counties,id',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'form_expires_at' => 'required|date|after:start_date',
        'daily_amount' => 'required|numeric|min:0'
    ]);

    $event = TrainingEvent::create([
        'name' => $validated['name'],
        'venue_name' => $validated['venue_name'],
        'county_id' => $validated['county_id'],
        'description' => $validated['description'],
        'start_date' => Carbon::parse($validated['start_date']),
        'end_date' => Carbon::parse($validated['end_date']),
        'form_expires_at' => Carbon::parse($validated['form_expires_at']),
        'daily_amount' => $validated['daily_amount'],
        'created_by' => auth()->id(),
    ]);

    return redirect()->route('training.show', $event->id)
        ->with('success', 'Training event created successfully.');
}

public function edit($id)
{
    $event = TrainingEvent::findOrFail($id);
    $counties = \DB::table('counties')->orderBy('name')->pluck('name', 'id');
    return view('training.edit', compact('event', 'counties'));
}

public function update(Request $request, $id)
{
    try {
        $event = TrainingEvent::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'venue_name' => 'required|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'form_expires_at' => 'required|date|after:start_date',
            'daily_amount' => 'required|numeric|min:0'
        ]);

        $event->update([
            'name' => $validated['name'],
            'venue_name' => $validated['venue_name'],
            'county_id' => $validated['county_id'],
            'description' => $validated['description'],
            'start_date' => Carbon::parse($validated['start_date']),
            'end_date' => Carbon::parse($validated['end_date']),
            'form_expires_at' => Carbon::parse($validated['form_expires_at']),
            'daily_amount' => $validated['daily_amount']
        ]);

        return redirect()->route('training.show', $event->id)
            ->with('success', 'Training event updated successfully.');
            
    } catch (\Exception $e) {
        \Log::error('Error updating training event:', [
            'event_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Failed to update training event. Please try again.');
    }
}


public function show($id)
{
    $event = TrainingEvent::with(['attendances' => function($query) {
        $query->latest();
    }, 'county'])->findOrFail($id);

    $user = auth()->user();
    if ($user->role->name === 'Regional_Coordinator') {
        $hasAccess = DB::table('regional_coordinator_counties')
            ->where('user_id', $user->id)
            ->where('county_id', $event->county_id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
    $this->updateAttendeeCompletionStatus($event);

    $canEditEvent = $user->hasPermission('training.edit');
    
    return view('training.show', compact('event', 'canEditEvent'));
}

private function updateAttendeeCompletionStatus($event)
{
    $event->attendances->groupBy('id_number')->each(function ($attendances) use ($event) {
        $latest = $attendances->first();
        $shouldBeCompleted = $this->shouldMarkCompleted($latest, $event);
        
        if ($shouldBeCompleted && !$latest->completed) {
            EventAttendance::where('id_number', $latest->id_number)
                ->where('training_event_id', $event->id)
                ->update(['completed' => true]);
        }
    });
}

public function getAttendanceHistory($id)
{
    $event = TrainingEvent::findOrFail($id);
    
    $event->attendances->groupBy('id_number')->each(function ($attendances) use ($event) {
        $latest = $attendances->first();
        $shouldBeCompleted = $this->shouldMarkCompleted($latest, $event);
        
        if ($shouldBeCompleted && !$latest->completed) {
            EventAttendance::where('id_number', $latest->id_number)
                ->where('training_event_id', $event->id)
                ->update(['completed' => true]);
        }
    });
    
    $attendances = EventAttendance::where('training_event_id', $id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($attendance) {
            return $attendance->created_at->format('Y-m-d');
        });

    return view('training.history', compact('event', 'attendances'));
}


public function generateRestrictedLink(Request $request, $id)
{
    try {
        $event = TrainingEvent::findOrFail($id);
        
        // Generate a simple token without location data
        $tokenData = [
            'event_id' => $event->id,
            'generated_at' => now()->timestamp,
            'generated_by' => auth()->id()
        ];
        
        $token = encrypt(json_encode($tokenData));

        // Update event without location enforcement
        $event->update([
            'enforce_location' => false,
            'location_token' => $token
        ]);

        $link = route('training.form', ['slug' => $event->slug, 'token' => $token]);

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate link: ' . $e->getMessage()
        ], 500);
    }
}


private function countUniqueDaysAttended($attendances)
{
    return $attendances->pluck('created_at')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })
        ->unique()
        ->count();
}

private function formatPhoneNumber($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
        return '254' . substr($phone, 1);
    } elseif (strlen($phone) === 9) {
        return '254' . $phone;
    } elseif (strlen($phone) === 12 && substr($phone, 0, 3) === '254') {
        return $phone;
    }
    
    throw new \Exception('Invalid phone number format. Please use format 07XXXXXXXX or 254XXXXXXXX');
}


public function banAttendee(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'id_number' => 'required|string',
            'reason' => 'required|string|max:500'
        ]);

        // Check user permission
        if (!auth()->user()->hasPermission('training.ban')) {
            throw new \Exception('Unauthorized to perform this action');
        }

        // Check if already banned
        $existingBan = BannedAttendee::where('id_number', $validated['id_number'])->first();
        if ($existingBan) {
            return back()->with('error', 'This attendee is already banned.');
        }

        DB::transaction(function () use ($validated) {
            BannedAttendee::create([
                'id_number' => $validated['id_number'],
                'reason' => $validated['reason'],
                'banned_at' => now(),
                'banned_by' => auth()->id()
            ]);

            // Log the ban action
            \Log::info('Attendee banned:', [
                'id_number' => $validated['id_number'],
                'banned_by' => auth()->id(),
                'reason' => $validated['reason']
            ]);
        });

        return back()->with('success', 'Attendee has been banned from future trainings');
    } catch (\Exception $e) {
        \Log::error('Error banning attendee:', [
            'error' => $e->getMessage(),
            'id_number' => $request->id_number ?? null,
            'user_id' => auth()->id()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Failed to ban attendee: ' . $e->getMessage());
    }
}


public function unbanAttendee($idNumber)
{
    try {
        $bannedAttendee = BannedAttendee::where('id_number', $idNumber)->firstOrFail();
        
        DB::transaction(function () use ($bannedAttendee) {
            // Log the unban action
            \Log::info('Attendee unbanned:', [
                'id_number' => $bannedAttendee->id_number,
                'unbanned_by' => auth()->id()
            ]);

            $bannedAttendee->delete();
        });

        return back()->with('success', 'Attendee has been unbanned successfully.');
    } catch (\Exception $e) {
        \Log::error('Error unbanning attendee:', [
            'error' => $e->getMessage(),
            'id_number' => $idNumber
        ]);
        
        return back()->with('error', 'Failed to unban attendee. Please try again.');
    }
}


private function shouldMarkCompleted($attendance, $event)
{
    $startDate = Carbon::parse($event->start_date)->startOfDay();
    $endDate = Carbon::parse($event->end_date)->endOfDay();
    $today = Carbon::now()->startOfDay();
    
    $totalDays = $startDate->diffInDays($endDate) + 1;

    if (($today->isAfter($endDate) && $attendance->days_attended > 0) || 
        $attendance->days_attended >= $totalDays) {
        return true;
    }
    
    return false;
}


public function showForm($slug, Request $request)
{
    if (config('training.maintenance_mode', false)) {
        return view('training.maintenance', [
            'message' => 'The training registration system is currently under maintenance. Please check back later.'
        ]);
    }

    $event = TrainingEvent::where('slug', $slug)->firstOrFail();
    
    if ($event->isExpired()) {
        return view('training.expired');
    }

    // Remove all location checks and directly show form
    $isFirstDay = Carbon::today()->isSameDay($event->start_date);
    $isLastDay = Carbon::today()->isSameDay($event->end_date);
    $totalDays = Carbon::parse($event->start_date)->diffInDays(Carbon::parse($event->end_date)) + 1;
    
    // Go directly to the form view
    return view('training.form', compact('event', 'isFirstDay', 'isLastDay', 'totalDays'));
}

private function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radius)
{
    $earthRadius = 6371000; 

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    
    $distance = $angle * $earthRadius;

    return $distance <= $radius;
}

public function searchAttendee(Request $request, $slug)
{
    try {
        $event = TrainingEvent::where('slug', $slug)->firstOrFail();
        $idNumber = $request->input('id_number');

        \Log::info('Search attendee request:', [
            'id_number' => $idNumber,
            'event_slug' => $slug
        ]);

        if (!$idNumber) {
            return response()->json([
                'found' => false,
                'message' => 'Please provide an ID number'
            ]);
        }

        // Check for banned status
        $bannedAttendee = BannedAttendee::where('id_number', $idNumber)->first();
        
        if ($bannedAttendee) {
            return response()->json([
                'found' => false,
                'banned' => true,
                'message' => 'You have been banned from attending trainings',
                'reason' => $bannedAttendee->reason,
                'banned_at' => $bannedAttendee->banned_at->format('M d, Y H:i')
            ]);
        }

        // Get latest attendance record
        $attendee = EventAttendance::where('training_event_id', $event->id)
            ->where('id_number', $idNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($attendee) {
            $allAttendances = EventAttendance::where('training_event_id', $event->id)
                ->where('id_number', $idNumber)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalDays = Carbon::parse($event->start_date)->diffInDays(Carbon::parse($event->end_date)) + 1;
            $isCompleted = $attendee->days_attended >= $totalDays;

            return response()->json([
                'found' => true,
                'data' => [
                    'name' => $attendee->name,
                    'email' => $attendee->email,
                    'phone_number' => $attendee->phone_number,
                    'designation' => $attendee->designation,
                    'days_attended' => $attendee->days_attended,
                    'total_amount' => number_format($attendee->total_amount, 2),
                    'attended_today' => EventAttendance::where('training_event_id', $event->id)
                        ->where('id_number', $idNumber)
                        ->whereDate('created_at', Carbon::today())
                        ->exists(),
                    'last_attendance_date' => $attendee->created_at->format('Y-m-d'),
                    'attendance_dates' => $allAttendances->pluck('created_at')->map->format('Y-m-d'),
                    'is_completed' => $isCompleted,
                    'total_days' => $totalDays
                ]
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Attendee not found',
            'canRegister' => true
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in searchAttendee:', [
            'slug' => $slug,
            'id_number' => $idNumber ?? null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'found' => false,
            'message' => 'Error searching for attendee'
        ], 500);
    }
}

public function storeAttendance(Request $request, $slug)
{
    try {
        return \DB::transaction(function() use ($request, $slug) {
            $event = TrainingEvent::where('slug', $slug)->firstOrFail();
            
            if ($event->isExpired()) {
                return back()->with('error', 'This form has expired');
            }

            // Ban check
            $isBanned = BannedAttendee::where('id_number', $request->input('id_number'))->exists();
            if ($isBanned) {
                return back()->with('error', 'You are not eligible to attend this training');
            }

            // Validate inputs
            $validationRules = [
                'id_number' => ['required', 'regex:/^[0-9]{5,9}$/'],
                'signature' => 'required|string'
            ];

            if (!$request->has('is_existing')) {
                $validationRules = array_merge($validationRules, [
                    'name' => 'required|string|max:255',
                    'designation' => 'required|string|max:100',
                    'phone_number' => ['required', 'string', 'regex:/^(?:254|\+254|0)[17][0-9]{8}$/'],
                    'email' => 'required|email'
                ]);
            }

            $validated = $request->validate($validationRules);

            // Check for attendance today
            $attendedToday = EventAttendance::where('training_event_id', $event->id)
                ->where('id_number', $validated['id_number'])
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if ($attendedToday) {
                return back()->with('error', 'Attendance already recorded for today');
            }

            // Get previous attendance records
            $previousAttendances = EventAttendance::where('training_event_id', $event->id)
                ->where('id_number', $validated['id_number'])
                ->orderBy('created_at', 'asc')
                ->get();

            // Get latest attendance record
            $latestAttendance = $previousAttendances->last();

            // Calculate days attended and completion status
            $daysAttended = $this->countUniqueDaysAttended($previousAttendances) + 1;
            $totalDays = Carbon::parse($event->start_date)->diffInDays(Carbon::parse($event->end_date)) + 1;
            $totalAmount = $event->daily_amount * $daysAttended;
            
            // Check completion status
            $isLastDay = Carbon::today()->isSameDay($event->end_date);
            $isAfterEnd = Carbon::today()->isAfter($event->end_date);
            $isCompleted = $isLastDay || $isAfterEnd || $daysAttended >= $totalDays;

            // Format phone number if needed
            $phoneNumber = $latestAttendance ? 
                $latestAttendance->phone_number : 
                $this->formatPhoneNumber($validated['phone_number']);

            // Prepare attendance data
            $attendanceData = [
                'training_event_id' => $event->id,
                'user_id' => auth()->id(),
                'name' => $latestAttendance ? $latestAttendance->name : $validated['name'],
                'designation' => $latestAttendance ? $latestAttendance->designation : $validated['designation'],
                'id_number' => $validated['id_number'],
                'phone_number' => $phoneNumber,
                'email' => $latestAttendance ? $latestAttendance->email : $validated['email'],
                'signature' => $validated['signature'],
                'is_authenticated_user' => auth()->check(),
                'is_registration' => !$latestAttendance,
                'days_attended' => $daysAttended,
                'total_amount' => $totalAmount,
                'completed' => $isCompleted,
                'phone_verified' => $latestAttendance ? $latestAttendance->phone_verified : false
            ];

            // Create attendance record
            $attendance = EventAttendance::create($attendanceData);

            // Format success message
            $message = $isCompleted ? 
                'Training completed successfully' : 
                'Attendance recorded successfully';

            $message .= sprintf(
                ' (Day %d of %d) - Total Amount: %s', 
                $daysAttended, 
                $totalDays,
                number_format($totalAmount, 2)
            );

            // Add phone verification notice if needed
            if ($isLastDay && !$attendance->phone_verified) {
                $message .= "\nPlease verify your phone number before leaving.";
            }

            return back()->with('success', $message);
        });
    } catch (\Exception $e) {
        \Log::error('Error in storeAttendance:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->except(['signature'])
        ]);
        
        return back()->with('error', 'An error occurred while recording attendance. Please try again.');
    }
}

    public function verifyPhone(Request $request, $slug)
    {
        try {
            return \DB::transaction(function() use ($request, $slug) {
                $event = TrainingEvent::where('slug', $slug)->firstOrFail();
                
                // Validate phone format
                $validated = $request->validate([
                    'id_number' => 'required|string',
                    'phone_number' => ['required', 'string', 'regex:/^(?:254|\+254|0)[17][0-9]{8}$/']
                ]);
    
                // Get attendee's latest record
                $attendee = EventAttendance::where('training_event_id', $event->id)
                    ->where('id_number', $validated['id_number'])
                    ->latest()
                    ->firstOrFail();
    
                // Format phone number
                $phone = $this->formatPhoneNumber($validated['phone_number']);
    
                // Update all attendance records for this attendee
                EventAttendance::where('training_event_id', $event->id)
                    ->where('id_number', $validated['id_number'])
                    ->update([
                        'phone_number' => $phone,
                        'phone_verified' => true,
                        'phone_verification_date' => now()
                    ]);
    
                return response()->json([
                    'success' => true,
                    'message' => 'Phone number updated successfully',
                    'phone' => $phone
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function verifyLocation(Request $request, $slug)
    {
        try {
            $event = TrainingEvent::where('slug', $slug)->firstOrFail();
            
            Log::info('Location verification request:', [
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'accuracy' => $request->input('accuracy'),
                'user_agent' => $request->header('User-Agent')
            ]);
    
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'accuracy' => 'required|numeric',
                'venue_lat' => 'required|numeric',
                'venue_lng' => 'required|numeric'
            ]);
    
            // Calculate distance between points
            $distance = $this->calculateDistance(
                $validated['latitude'],
                $validated['longitude'],
                $validated['venue_lat'],
                $validated['venue_lng']
            );
    
            // Set base tolerance radius (75m) plus additional based on GPS accuracy
            $toleranceRadius = 75;
            $adjustedTolerance = $toleranceRadius + ($validated['accuracy'] / 2);
    
            Log::info('Location verification calculation:', [
                'distance' => $distance,
                'base_tolerance' => $toleranceRadius,
                'gps_accuracy' => $validated['accuracy'],
                'adjusted_tolerance' => $adjustedTolerance
            ]);
    
            if ($distance <= $adjustedTolerance) {
                // Store verified location in session
                session(['venue_location' => [
                    'latitude' => $validated['venue_lat'],
                    'longitude' => $validated['venue_lng'],
                    'radius' => $toleranceRadius
                ]]);
    
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('training.form', ['slug' => $event->slug]),
                    'distance' => round($distance),
                    'accuracy' => round($validated['accuracy'])
                ]);
            }
    
            return response()->json([
                'success' => false,
                'distance' => round($distance),
                'required_distance' => $toleranceRadius,
                'message' => sprintf(
                    "You appear to be %dm from the venue. Please ensure you're at the correct location.",
                    round($distance)
                )
            ]);
    
        } catch (\Exception $e) {
            Log::error('Location verification error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Location verification failed. Please try again.'
            ], 500);
        }
    }
    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Earth's radius in meters
        $earthRadius = 6371000;
    
        // Convert coordinates to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        // Calculate differences
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
    
        // Haversine formula
        $a = sin($dLat/2) * sin($dLat/2) +
             cos($lat1) * cos($lat2) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Calculate distance in meters
        return $earthRadius * $c;
    }
    

    public function export($id)
    {
        $event = TrainingEvent::with(['attendances' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Group attendances by ID number
        $attendees = $event->attendances
            ->groupBy('id_number')
            ->map(function ($group) {
                return $group->first();
            });

        // Calculate total days
        $totalDays = Carbon::parse($event->start_date)->diffInDays(Carbon::parse($event->end_date)) + 1;

        // Get attendance dates
        $attendanceDates = $event->attendances
            ->groupBy('id_number')
            ->map(function ($attendances) {
                return $attendances->pluck('created_at')
                    ->map(function ($date) {
                        return $date->format('Y-m-d');
                    })
                    ->unique()
                    ->values()
                    ->all();
            });

        // Create temp directory if it doesn't exist
        $tempDir = storage_path('app/pdf-temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Configure mPDF
        $config = [
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 10,
            'margin_footer' => 10,
            'tempDir' => $tempDir
        ];

        try {
            // Initialize mPDF with config
            $mpdf = new Mpdf($config);

            // Set document metadata
            $mpdf->SetTitle($event->name . ' - Attendance Report');
            $mpdf->SetAuthor(config('app.name'));
            $mpdf->SetCreator(config('app.name'));

            // Generate HTML content
            $html = view('training.exports.mpdf', compact(
                'event', 
                'attendees', 
                'totalDays', 
                'attendanceDates'
            ))->render();

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Set filename
            $filename = Str::slug($event->name) . '-attendances.pdf';

            // Output PDF
            return response()->make($mpdf->Output($filename, 'I'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating PDF. Please try again.');
        }
    }


public function destroy($id)
{
    try {
        \DB::beginTransaction();
        
        \Log::info('Attempting to delete event:', ['id' => $id]);
        
        $event = TrainingEvent::findOrFail($id);
        
        // Check if event has attendances
        $attendanceCount = $event->attendances()->count();
        \Log::info('Event attendance count:', ['count' => $attendanceCount]);
        
        if ($attendanceCount > 0) {
            \DB::rollBack();
            \Log::warning('Cannot delete event - has attendances', ['count' => $attendanceCount]);
            return back()->with('error', "Cannot delete event with existing attendances ($attendanceCount found).");
        }
        
        // Attempt to delete
        $deleted = $event->delete();
        \Log::info('Delete attempt result:', ['deleted' => $deleted]);
        
        if (!$deleted) {
            \DB::rollBack();
            throw new \Exception('Failed to delete event');
        }
        
        \DB::commit();
        \Log::info('Event deleted successfully');
        
        return redirect()->route('training.index')
            ->with('success', 'Training event deleted successfully.');
            
    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('Error deleting training event:', [
            'event_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Failed to delete training event: ' . $e->getMessage());
    }
}

public function exportExcel($id)
{
    $event = TrainingEvent::findOrFail($id);
    $filename = Str::slug($event->name) . '-attendances.xlsx';
    return Excel::download(new TrainingAttendancesExport($event), $filename);
}
}