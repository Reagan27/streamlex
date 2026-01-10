<?php

namespace Vanguard\Http\Controllers\Web\Projects;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Projects;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
 public function index()
{
    // Debug - check if class exists
    // if (!class_exists('Vanguard\Project')) {
    //     dd(
    //         'Vanguard\Project not found',
    //         'Check if file exists at: ' . app_path('Models/Project.php'),
    //         'Current namespace: ' . __NAMESPACE__
    //     );
    // }
    
    $projects = Projects::orderBy('name')->paginate(20);
    return view('projects.index', compact('projects'));
}

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'budget'      => 'nullable|numeric|min:0',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Projects::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }
}
