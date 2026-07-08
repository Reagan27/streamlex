<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Projects extends Plugin
{
  
  
    public function sidebar(): ?Item
    {
        return Item::create(__('Projects'))
            ->route('projects.index')
            ->icon('fas fa-briefcase')
            ->active('projects*')
            ->permissions('project.view');
    }

    public function permissions(): array
{
    return [
        'project.view'   => 'View Projects',
        'project.manage' => 'Manage Projects (Create/Edit/Delete)',
    ];
}

}