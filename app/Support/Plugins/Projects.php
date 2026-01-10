<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Projects extends Plugin
{
    public function sidebar(): ?Item
    {
        // If user has NO project permissions → don't show the menu at all
        $user = auth()->user();

        if (! $user->hasPermission('project.view') && ! $user->hasPermission('project.manage')) {
            return null;
        }

        // Main "Projects" dropdown
        return Item::create(__('Projects'))
            ->href('#projectsMenu')
            ->icon('fas fa-project-diagram')
            ->active('projects*')
            ->addChildren([

                Item::create(__('All Projects'))
                    ->route('projects.index')
                    ->icon('fas fa-list-ul')
                    ->active('projects')
                    ->permissions('project.view'),

                Item::create(__('Create Project'))
                    ->route('projects.create')
                    ->icon('fas fa-plus-circle')
                    ->permissions('project.manage'),

                Item::create(__('My Projects'))
                    ->route('projects.my')
                    ->icon('fas fa-user-check')
                    ->permissions('project.view'),

                Item::create(__('Project Reports'))
                    ->route('projects.reports')
                    ->icon('fas fa-chart-pie')
                    ->permissions('project.reports'),
            ]);
    }

    /**
     * Register plugin permissions (will appear in Roles & Permissions)
     */
    public function permissions(): array
    {
        return [
            'project.view'    => 'View Projects',
            'project.manage'  => 'Create/Edit/Delete Projects',
            'project.reports' => 'View Project Reports',
        ];
    }
}
