<?php
require 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Vanguard\AdminContract;
use Vanguard\User;
use Vanguard\Projects;

echo "=== CONTRACTS IN DB ===\n";
$contracts = AdminContract::take(5)->get(['id', 'title', 'role_id', 'project_id', 'status', 'active_for_onboarding']);
foreach ($contracts as $c) {
    echo "ID: {$c->id} | Title: {$c->title} | Role: {$c->role_id} | Project: " . ($c->project_id ?? 'NULL') . " | Status: {$c->status} | Active: " . ($c->active_for_onboarding ? 'YES' : 'NO') . "\n";
}

echo "\n=== RECENT USERS ===\n";
$users = User::orderBy('id', 'desc')->take(3)->get();
foreach ($users as $user) {
    $projectIds = $user->projects->pluck('id')->implode(', ');
    $active = $user->getActiveProjectId();
    echo "User {$user->id} ({$user->first_name}) | Projects: " . ($projectIds ?: 'NONE') . " | Active: " . ($active ?? 'NULL') . "\n";
}

echo "\n=== PROJECTS ===\n";
$projects = Projects::take(5)->get(['id', 'name']);
foreach ($projects as $p) {
    echo "ID: {$p->id} | Name: {$p->name}\n";
}

echo "\n=== USER-PROJECT ASSIGNMENTS (projects_user table) ===\n";
$assignments = DB::table('projects_user')->take(5)->get();
foreach ($assignments as $a) {
    echo "User {$a->user_id} -> Project {$a->project_id} | Active: " . ($a->is_active_project ? 'YES' : 'NO') . "\n";
}
