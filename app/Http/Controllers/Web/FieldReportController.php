<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanguard\FieldReport;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Vanguard\County;

class FieldReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = FieldReport::with(['creator', 'creator.role', 'county'])
            ->withCount('attachments');


        $currentUser = auth()->user();
        $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();

       
        if (!$currentUser->isAdmin()) {
            $userCounties = $currentUser->counties->pluck('id')->toArray();
            
            if ($currentUser->hasRole('Regional_Coordinator')) {
                $query->whereIn('county_id', $userCounties);
            } elseif ($currentUser->hasRole('County_Coordinator')) {
                $query->where('county_id', $currentUser->county_id);
            } else {
                $query->where('created_by', $currentUser->id);
            }
        }

       
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }




        if ($activeProjectId) {
            $query->whereHas('creator.projects', function ($q) use ($activeProjectId) {
                $q->where('projects.id', $activeProjectId);
            });
        }   

       
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        
        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        }

        $reports = $query->latest()->paginate(20);
        $counties = County::orderBy('name')->get();

        return view('field-reports.index', compact('reports', 'counties'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $counties = County::orderBy('name')->get();
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties;
        } else {
            $counties = County::where('id', $user->county_id)->get();
        }

        return view('field-reports.create', compact('counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'content' => 'nullable|string',
            'summary' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:2048', 
            'status' => 'required|in:draft,submitted'
        ]);

        try {
            DB::beginTransaction();

            $report = FieldReport::create([
                'title' => $request->title,
                'county_id' => $request->county_id,
                'content' => $request->content,
                'summary' => $request->summary,
                'recommendations' => $request->recommendations,
                'status' => $request->status,
                'created_by' => auth()->id()
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('field-reports/attachments', 'public');
                    
                    $report->attachments()->create([
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('field-reports.show', $report)
                ->with('success', 'Field report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create field report: ' . $e->getMessage())->withInput();
        }
    }

    public function show(FieldReport $report)
    {
        $report->load(['creator', 'approver', 'county', 'attachments']);
        return view('field-reports.show', compact('report'));
    }

    public function edit(FieldReport $report)
    {
       
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be edited.');
        }
    
       
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $counties = County::orderBy('name')->get();
        } elseif ($user->hasRole('Regional_Coordinator')) {
            $counties = $user->counties;
        } else {
          
            $counties = County::where('id', $user->county_id)->get();
        }
    
        
        if (!$user->isAdmin() && !$user->hasRole('Regional_Coordinator')) {
           
            if ($report->county_id !== $user->county_id) {
                return back()->with('error', 'You can only edit reports for your assigned county.');
            }
        }
    
        return view('field-reports.edit', compact('report', 'counties'));
    }

    public function update(Request $request, FieldReport $report)
{
    if ($report->status === 'approved') {
        return back()->with('error', 'Approved reports cannot be updated.');
    }

    $user = auth()->user();

   
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'summary' => 'nullable|string',
        'recommendations' => 'nullable|string',
        'attachments.*' => 'nullable|file|max:2048', 
        'status' => 'required|in:draft,submitted'
    ]);

    
    if ($user->isAdmin() || $user->hasRole('Regional_Coordinator')) {
        $validatedData['county_id'] = $request->validate([
            'county_id' => 'required|exists:counties,id'
        ])['county_id'];
    } else {
       
        $validatedData['county_id'] = $user->county_id;
    }

    try {
        DB::beginTransaction();

        $report->update($validatedData);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('field-reports/attachments', 'public');
                
                $report->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize()
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('field-reports.show', $report)
            ->with('success', 'Field report updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update field report: ' . $e->getMessage());
    }
}


    public function destroy(FieldReport $report)
    {
        if ($report->status === 'approved') {
            return back()->with('error', 'Approved reports cannot be deleted.');
        }

        try {
            DB::beginTransaction();

          
            foreach ($report->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $report->attachments()->delete();
            $report->delete();

            DB::commit();

            return redirect()
                ->route('field-reports.index')
                ->with('success', 'Field report deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete field report.');
        }
    }

    public function approve(FieldReport $report)
    {
       
        if (!$report->can_approve) {
            return back()->with('error', 'You do not have permission to approve this report.');
        }
    
      
        if ($report->status !== 'submitted') {
            return back()->with('error', 'Only submitted reports can be approved.');
        }
    
        try {
            DB::beginTransaction();
    
            $report->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
    
            DB::commit();
    
            return back()->with('success', 'Report approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve report.');
        }
    }

    public function removeAttachment($reportId, $attachmentId)
    {
        $report = FieldReport::findOrFail($reportId);
        
        if ($report->status === 'approved') {
            return back()->with('error', 'Cannot remove attachments from approved reports.');
        }

        $attachment = $report->attachments()->findOrFail($attachmentId);
        
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Attachment removed successfully.');
    }
}