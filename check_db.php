<?php
// Simple database debug script

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== RECENT CONTRACTS ===\n";
$contracts = DB::table('admin_contracts')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'title', 'role_id', 'project_id', 'status', 'active_for_onboarding']);

foreach ($contracts as $c) {
    $projStr = $c->project_id ? "Project: {$c->project_id}" : "Project: NULL";
    $activeStr = $c->active_for_onboarding ? "Active: YES" : "Active: NO";
    echo "ID: {$c->id} | Title: {$c->title} | Role: {$c->role_id} | {$projStr} | {$activeStr}\n";
}

echo "\n=== RECENT USERS WITH PROJECTS ===\n";
$users = DB::table('users')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'first_name', 'role_id']);

foreach ($users as $u) {
    $projects = DB::table('projects_user')
        ->where('user_id', $u->id)
        ->get(['project_id', 'is_active_project']);
    
    $projectStr = '';
    if (count($projects) > 0) {
        foreach ($projects as $p) {
            $active = $p->is_active_project ? 'active' : 'inactive';
            $projectStr .= "#" . $p->project_id . " ({$active}) ";
        }
    } else {
        $projectStr = "NO PROJECTS";
    }
    
    echo "User {$u->id} ({$u->first_name}) Role {$u->role_id} | {$projectStr}\n";
}

echo "\n=== USER 16353 DETAIL ===\n";
$user16353 = DB::table('users')->find(16353);
if ($user16353) {
    echo "User found: {$user16353->first_name} {$user16353->last_name} | Role: {$user16353->role_id}\n";
    
    $projects16353 = DB::table('projects_user')
        ->where('user_id', 16353)
        ->get();
    
    if (count($projects16353) > 0) {
        echo "Projects assigned to user 16353:\n";
        foreach ($projects16353 as $p) {
            $active = $p->is_active_project ? "YES" : "NO";
            echo "  Project ID: {$p->project_id} | is_active_project: {$active}\n";
        }
    } else {
        echo "NO PROJECTS ASSIGNED TO USER 16353\n";
    }
} else {
    echo "User 16353 NOT FOUND\n";
}

echo "\n=== ALL PROJECTS ===\n";
$allProjects = DB::table('projects')->get(['id', 'name']);
foreach ($allProjects as $p) {
    echo "Project {$p->id}: {$p->name}\n";
}

echo "\n";
