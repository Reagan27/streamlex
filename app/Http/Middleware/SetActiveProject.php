<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Vanguard\Projects;

class SetActiveProject 
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Get active project ID from session or user's default
            $activeProjectId = session('active_project_id') ?? $user->getActiveProjectId();
            
            // Share with all views
            if ($activeProjectId) {
                $activeProject = Projects::find($activeProjectId);
                view()->share('currentActiveProject', $activeProject);
                view()->share('activeProjectId', $activeProjectId);
            }
        }

        return $next($request);
    }
}