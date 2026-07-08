<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Meeting;
use Vanguard\MeetingParticipant;
use Vanguard\MeetingDocument;
use Vanguard\MeetingAction;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\Role;
use Vanguard\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:meetings.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:meetings.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:meetings.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:meetings.delete', ['only' => ['destroy']]);
    }

    private function getParticipantRoles(): array
    {
        return Role::orderBy('display_name')
            ->pluck('display_name', 'name')
            ->toArray();
    }

    /**
     * Display a listing of meetings
     */
    public function index(Request $request)
    {
        $query = Meeting::with(['organizer', 'participants', 'documents'])
            ->withCount(['participants', 'actions']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('meeting_type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = "%{$request->search}%";
            $query->where('title', 'like', $search)
                ->orWhere('description', 'like', $search);
        }

        $meetings = $query->latest('meeting_date')->paginate(15);

        $statuses = ['Draft', 'Scheduled', 'In Progress', 'Completed', 'Archived'];
        $types = ['Physical', 'Virtual'];

        return view('meetings.index', compact('meetings', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new meeting
     */
    public function create()
    {
        $users = User::where('status', UserStatus::ACTIVE->value)
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as display_name"), 'id')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('display_name', 'id');

        $roles = $this->getParticipantRoles();

        return view('meetings.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created meeting
     */
    public function store(Request $request)
    {
        $participantRoles = array_keys($this->getParticipantRoles());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_type' => 'required|in:Physical,Virtual',
            'meeting_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            
            // Physical meeting fields
            'venue_name' => 'required_if:meeting_type,Physical|nullable|string|max:255',
            'address' => 'required_if:meeting_type,Physical|nullable|string',
            'room_number' => 'nullable|string|max:50',
            'location_map_link' => 'nullable|url',
            
            // Virtual meeting fields
            'meeting_link' => 'required_if:meeting_type,Virtual|nullable|url',
            'meeting_platform' => 'required_if:meeting_type,Virtual|nullable|in:Zoom,Google Meet,Teams,Other',
            
            // Participants
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'participant_roles' => 'nullable|array',
            'participant_roles.*' => ['required', Rule::in($participantRoles)],
        ]);

        try {
            DB::beginTransaction();

            // Create the meeting
            $meeting = Meeting::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'meeting_type' => $validated['meeting_type'],
                'status' => 'Scheduled',
                'meeting_date' => Carbon::parse($validated['meeting_date'])->toDateTimeString(),
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'venue_name' => $validated['venue_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'room_number' => $validated['room_number'] ?? null,
                'location_map_link' => $validated['location_map_link'] ?? null,
                'meeting_link' => $validated['meeting_link'] ?? null,
                'meeting_platform' => $validated['meeting_platform'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Add organizer as chairperson
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => auth()->id(),
                'role' => 'Chairperson',
                'attendance_status' => 'Confirmed',
            ]);

            // Add other participants
            if ($request->filled('participants')) {
                foreach ($request->participants as $index => $userId) {
                    if ($userId != auth()->id()) {
                        $role = $request->participant_roles[$index] ?? 'Participant';
                        MeetingParticipant::create([
                            'meeting_id' => $meeting->id,
                            'user_id' => $userId,
                            'role' => $role,
                            'attendance_status' => 'Invited',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('meetings.show', $meeting->id)
                ->with('success', 'Meeting created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create meeting: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified meeting
     */
    public function show(Meeting $meeting)
    {
        $meeting->load(['organizer', 'participants.user', 'documents.uploadedBy', 'actions.assignedTo']);

        $openActions = $meeting->actions()->open()->get();
        $completedActions = $meeting->actions()->completed()->get();

        $users = User::where('status', UserStatus::ACTIVE->value)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $roles = $this->getParticipantRoles();

        return view('meetings.show', compact('meeting', 'openActions', 'completedActions', 'users', 'roles'));
    }

    /**
     * Show the form for editing the meeting
     */
    public function edit(Meeting $meeting)
    {
        $meeting->load(['participants.user']);
        $users = User::where('status', UserStatus::ACTIVE->value)
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as display_name"), 'id')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('display_name', 'id');

        $roles = $this->getParticipantRoles();
        
        return view('meetings.edit', compact('meeting', 'users', 'roles'));
    }

    /**
     * Update the specified meeting
     */
    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_type' => 'required|in:Physical,Virtual',
            'status' => 'required|in:Draft,Scheduled,In Progress,Completed,Archived',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            
            'venue_name' => 'required_if:meeting_type,Physical|nullable|string|max:255',
            'address' => 'required_if:meeting_type,Physical|nullable|string',
            'room_number' => 'nullable|string|max:50',
            'location_map_link' => 'nullable|url',
            
            'meeting_link' => 'required_if:meeting_type,Virtual|nullable|url',
            'meeting_platform' => 'required_if:meeting_type,Virtual|nullable|in:Zoom,Google Meet,Teams,Other',
        ]);

        $meeting->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meeting_type' => $validated['meeting_type'],
            'status' => $validated['status'],
            'meeting_date' => Carbon::parse($validated['meeting_date'])->toDateTimeString(),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'venue_name' => $validated['venue_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'room_number' => $validated['room_number'] ?? null,
            'location_map_link' => $validated['location_map_link'] ?? null,
            'meeting_link' => $validated['meeting_link'] ?? null,
            'meeting_platform' => $validated['meeting_platform'] ?? null,
        ]);

        return redirect()->route('meetings.show', $meeting->id)
            ->with('success', 'Meeting updated successfully!');
    }

    /**
     * Delete the specified meeting
     */
    public function destroy(Meeting $meeting)
    {
        try {
            // Delete associated files
            foreach ($meeting->documents as $document) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }

            $meeting->delete();

            return redirect()->route('meetings.index')
                ->with('success', 'Meeting deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete meeting: ' . $e->getMessage());
        }
    }

    /**
     * Add participant to meeting
     */
    public function addParticipant(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(array_keys($this->getParticipantRoles()))],
        ]);

        // Check if participant already exists
        $existing = MeetingParticipant::where('meeting_id', $meeting->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Participant already added'], 400);
        }

        MeetingParticipant::create([
            'meeting_id' => $meeting->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'attendance_status' => 'Invited',
        ]);

        return response()->json(['success' => 'Participant added successfully']);
    }

    /**
     * Remove participant from meeting
     */
    public function removeParticipant(Meeting $meeting, MeetingParticipant $participant)
    {
        if ($participant->meeting_id !== $meeting->id) {
            return response()->json(['error' => 'Invalid participant'], 400);
        }

        $participant->delete();

        return response()->json(['success' => 'Participant removed successfully']);
    }

    /**
     * Update participant status
     */
    public function updateParticipantStatus(Request $request, Meeting $meeting, MeetingParticipant $participant)
    {
        $validated = $request->validate([
            'attendance_status' => 'required|in:Invited,Confirmed,Declined,Attended',
        ]);

        $participant->update($validated);

        return response()->json(['success' => 'Participant status updated']);
    }

    /**
     * Upload meeting document
     */
    public function uploadDocument(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:Minutes,Audio,Video,Attendance,Transcript',
            'file' => 'required|file|max:104857600', // 100MB max
            'remarks' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('meetings/' . $meeting->id, $fileName, 'public');

            MeetingDocument::create([
                'meeting_id' => $meeting->id,
                'document_type' => $validated['document_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'remarks' => $validated['remarks'],
                'uploaded_by' => auth()->id(),
            ]);

            return response()->json(['success' => 'Document uploaded successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload document: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Delete meeting document
     */
    public function deleteDocument(Meeting $meeting, MeetingDocument $document)
    {
        if ($document->meeting_id !== $meeting->id) {
            return response()->json(['error' => 'Invalid document'], 400);
        }

        try {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json(['success' => 'Document deleted successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete document'], 400);
        }
    }

    /**
     * Add action item to meeting
     */
    public function addAction(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
        ]);

        MeetingAction::create([
            'meeting_id' => $meeting->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'due_date' => $validated['due_date'],
            'status' => 'Open',
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => 'Action item created successfully']);
    }

    /**
     * Update action item
     */
    public function updateAction(Request $request, Meeting $meeting, MeetingAction $action)
    {
        if ($action->meeting_id !== $meeting->id) {
            return response()->json(['error' => 'Invalid action'], 400);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:Open,In Progress,Completed,Overdue',
        ]);

        $action->update($validated);

        return response()->json(['success' => 'Action item updated successfully']);
    }

    /**
     * Delete action item
     */
    public function deleteAction(Meeting $meeting, MeetingAction $action)
    {
        if ($action->meeting_id !== $meeting->id) {
            return response()->json(['error' => 'Invalid action'], 400);
        }

        $action->delete();

        return response()->json(['success' => 'Action item deleted successfully']);
    }
}
