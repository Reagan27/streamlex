$request->validate([
    'user_id' => 'required|exists:users,id',
    'engagement_type' => 'required|in:consultant,employee,parttime',
    'duration_type' => 'required|in:days,months,years',
]);

$contract = AdminContract::with('role')->findOrFail($id);