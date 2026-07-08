<?php
// Database debugging script
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\User;
use App\AdminContract;

echo "\n=== DATABASE DEBUG ===\n\n";

// 1. Check recent admin_contracts
echo "1. RECENT ADMIN_CONTRACTS:\n";
echo str_repeat("-", 80) . "\n";
$contracts = DB::table('admin_contracts')
    ->select('id', 'title', 'role_id', 'status', 'active_for_onboarding', 'project_id', 'created_at')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get();

foreach ($contracts as $contract) {
    echo "ID: {$contract->id} | Title: {$contract->title} | Role: {$contract->role_id} | ";
    echo "Status: {$contract->status} | Active: {$contract->active_for_onboarding} | ";
    echo "Project ID: " . ($contract->project_id ?? 'NULL') . " | Created: {$contract->created_at}\n";
}

// 2. Check recent users  
echo "\n2. RECENT USERS:\n";
echo str_repeat("-", 80) . "\n";
$users = DB::table('users')
    ->select('id', 'email', 'first_name', 'last_name', 'role_id', 'county_id', 'created_at')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get();

foreach ($users as $user) {
    echo "ID: {$user->id} | {$user->first_name} {$user->last_name} | Email: {$user->email} | Role: {$user->role_id} | County: {$user->county_id}\n";
}

// 3. Check projects_user assignments
echo "\n3. RECENT PROJECTS_USER ASSIGNMENTS:\n";
echo str_repeat("-", 80) . "\n";
$assignments = DB::table('projects_user')
    ->select('id', 'user_id', 'project_id', 'is_active_project', 'created_at')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

if (count($assignments) > 0) {
    foreach ($assignments as $assign) {
        echo "ID: {$assign->id} | User: {$assign->user_id} | Project: {$assign->project_id} | Active: {$assign->is_active_project} | Created: {$assign->created_at}\n";
    }
} else {
    echo "No projects_user records found!\n";
}

// 4. Check if user 16353 exists and has projects
echo "\n4. USER 16353 DETAILS:\n";
echo str_repeat("-", 80) . "\n";
$user16353 = DB::table('users')->find(16353);
if ($user16353) {
    echo "User found: {$user16353->first_name} {$user16353->last_name}\n";
    
    // Check project assignments for this user
    $userProjects = DB::table('projects_user')
        ->where('user_id', 16353)
        ->get();
    
    if (count($userProjects) > 0) {
        echo "Projects assigned:\n";
        foreach ($userProjects as $proj) {
            echo "  - Project ID: {$proj->project_id} | is_active_project: {$proj->is_active_project}\n";
        }
    } else {
        echo "NO PROJECTS ASSIGNED TO THIS USER!\n";
    }
} else {
    echo "User 16353 not found.\n";
}

// 5. Check all projects
echo "\n5. ALL PROJECTS:\n";
echo str_repeat("-", 80) . "\n";
$projects = DB::table('projects')->select('id', 'name')->get();
foreach ($projects as $proj) {
    echo "ID: {$proj->id} | Name: {$proj->name}\n";
}

echo "\n=== END DEBUG ===\n\n";
