<?php
namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class setActiveProject {
    // app/Http/Middleware/SetActiveProject.php
public function handle($request, $next)
{
    if (auth()->check()) {
        $user = auth()->user();

        // Admin/Manager bypass
        if ($user->hasRole(['Admin', 'Manager'])) {
            return $next($request);
        }

        $projectId = $request->session()->get('current_project_id')
                   ?? optional($user->activeProject())->id;

        if ($projectId) {
            view()->share('currentProject', Project::find($projectId));
        }
    }

    return $next($request);
}
}