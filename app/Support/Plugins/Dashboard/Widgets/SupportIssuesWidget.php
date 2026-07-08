<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Illuminate\Support\Facades\DB;
use Vanguard\IssuesCategory;

class SupportIssuesWidget extends Widget
{
    public ?string $width = '12';

    protected string|\Closure|array $permissions = 'users.create';

    protected array $issuesData;

    public function render(): View
    {
        return view('plugins.dashboard.widgets.support-issues', [
            'issuesData' => $this->getIssuesData(),
            'categories' => $this->getCategories(),
        ]);
    }

    public function scripts(): View
    {
        return view('plugins.dashboard.widgets.support-issues-scripts', [
            'issuesData' => $this->getIssuesData(),
            'categories' => $this->getCategories(),
        ]);
    }
        private function getIssuesData(): array
{
    if (isset($this->issuesData)) {
        return $this->issuesData;
    }

    $currentUser = auth()->user();
    $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();

    $query = DB::table('support_issues')
        ->join('issues_categories', 'support_issues.category_id', '=', 'issues_categories.id')
        ->join('users', 'support_issues.user_id', '=', 'users.id')
        ->select(
            'issues_categories.name as category_name',
            'support_issues.status',
            DB::raw('COUNT(*) as count')
        );

    // ADD PROJECT FILTER
    if ($activeProjectId) {
        $query->whereExists(function ($q) use ($activeProjectId) {
            $q->select(DB::raw(1))
              ->from('projects_user')
              ->whereColumn('projects_user.user_id', 'users.id')
              ->where('projects_user.project_id', $activeProjectId);
        });
    }

    $rawData = $query->groupBy('category_name', 'status')->get();

        $categories = $this->getCategories();
        $statuses = ['Pending', 'Open', 'Closed'];

        $this->issuesData = array_fill_keys($categories, array_fill_keys($statuses, 0));

        foreach ($rawData as $item) {
            $this->issuesData[$item->category_name][$item->status] = $item->count;
        }

        return $this->issuesData;
    }

    private function getCategories(): array
    {
        return IssuesCategory::pluck('name')->toArray();
    }
}