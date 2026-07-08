<?php

namespace Vanguard\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Vanguard\Projects;

class ActiveProjectComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        
        
        $activeProject = $user->activeProject();
        $view->with('currentActiveProject', $activeProject);
        
       
        $viewName = $view->getName();
        
        
        if ($viewName === 'projects.index' || str_contains($viewName, 'projects.index')) {
            return;
        }
        
       
        if ($user->isAdmin() || $user->hasRole(['Manager', 'Finance'])) {
            $projects = Projects::orderBy('name')->get();
        } elseif ($user->role && $user->role->name === 'Regional_Coordinator') {
            $countyIds = $user->counties()->pluck('counties.id');
            $projects = Projects::whereHas('users', function ($q) use ($countyIds) {
                $q->whereIn('county_id', $countyIds);
            })->orderBy('name')->get();
        } elseif ($user->role && $user->role->name === 'County_Coordinator') {
            $projects = Projects::whereHas('users', function ($q) use ($user) {
                $q->where('county_id', $user->county_id);
            })->orderBy('name')->get();
        } else {
            $projects = collect();
        }
        
        $view->with('projects', $projects);
    }
}