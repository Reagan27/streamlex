<?php
// Custom API for field activities (for fam.blade.php)
use Vanguard\Http\Controllers\Api\FieldActivityApiController;
use Vanguard\Http\Controllers\Api\FieldActivityLogController;
use Vanguard\Http\Controllers\Api\ExcelDataController;
use Vanguard\Http\Controllers\Api\BotWebhookController;
use App\Http\Controllers\Web\CoachRequisitionController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Vanguard\County;
use App\Team;
use Vanguard\Region;

Route::get('/counties/{county}/subcounties', function (County $county) {
    $subcounties = $county->subcounties()->get(['id', 'name']);
    return response()->json(['success' => true, 'data' => $subcounties]);
});


// Field Activities API (for fam.blade.js logsheet)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('field-activities', [FieldActivityApiController::class, 'index']);
    Route::get('field-activities/{id}/logs', [FieldActivityApiController::class, 'logs']);
    Route::post('field-activities/{id}/logs', [FieldActivityApiController::class, 'storeLogs']);
    Route::post('field-activities/{id}/set-status', [FieldActivityApiController::class, 'setStatus']);
});

// Keep resource for legacy endpoints if needed
Route::apiResource('field-activities', FieldActivityApiController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/coach-requisitions', [CoachRequisitionController::class, 'index']);
    Route::post('/coach-requisitions', [CoachRequisitionController::class, 'store']);
    Route::get('/coach-requisitions/pending-my-approval', [CoachRequisitionController::class, 'pendingMyApproval']);
    Route::get('/coach-requisitions/{id}', [CoachRequisitionController::class, 'show']);
    Route::put('/coach-requisitions/{id}', [CoachRequisitionController::class, 'update']);
    Route::post('/coach-requisitions/{id}/submit', [CoachRequisitionController::class, 'submit']);
    Route::post('/coach-requisitions/{id}/approve', [CoachRequisitionController::class, 'approve']);
    Route::post('/coach-requisitions/{id}/reject', [CoachRequisitionController::class, 'reject']);
    Route::get('/coach-requisitions/{id}/approvals', [CoachRequisitionController::class, 'approvals']);
    Route::post('/coach-requisitions/{id}/proposed-coaches', [CoachRequisitionController::class, 'addProposedCoach']);
    Route::delete('/coach-requisitions/{id}/proposed-coaches/{coach_id}', [CoachRequisitionController::class, 'removeProposedCoach']);
});

Route::post('login', 'Auth\AuthController@token');
Route::post('login/social', 'Auth\SocialLoginController@index');
Route::post('logout', 'Auth\AuthController@logout');

Route::post('register', 'Auth\RegistrationController@index')->middleware('registration');

Route::group(['middleware' => ['guest', 'password-reset']], function () {
    Route::post('password/remind', 'Auth\Password\RemindController@index');
    Route::post('password/reset', 'Auth\Password\ResetController@index');
});

Route::group(['middleware' => ['auth', 'registration']], function () {
    Route::post('email/resend', 'Auth\VerificationController@resend');
    Route::post('email/verify', 'Auth\VerificationController@verify');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('me', 'Profile\DetailsController@index');
    Route::patch('me/details', 'Profile\DetailsController@update');
    Route::patch('me/details/auth', 'Profile\AuthDetailsController@update');
    Route::post('me/avatar', 'Profile\AvatarController@update');
    Route::delete('me/avatar', 'Profile\AvatarController@destroy');
    Route::put('me/avatar/external', 'Profile\AvatarController@updateExternal');
    Route::get('me/sessions', 'Profile\SessionsController@index');

    Route::group(['middleware' => 'two-factor'], function () {
        Route::put('me/2fa', 'Profile\TwoFactorController@update');
        Route::post('me/2fa/verify', 'Profile\TwoFactorController@verify');
        Route::delete('me/2fa', 'Profile\TwoFactorController@destroy');
    });

    Route::get('stats', 'StatsController@index');

    Route::apiResource('users', 'Users\UsersController')->except('show');
    Route::get('users/search', 'Users\UsersController@search');
    Route::get('users/{userId}', 'Users\UsersController@show');

    Route::post('users/{user}/avatar', 'Users\AvatarController@update');
    Route::put('users/{user}/avatar/external', 'Users\AvatarController@updateExternal');
    Route::delete('users/{user}/avatar', 'Users\AvatarController@destroy');

    Route::group(['middleware' => 'two-factor'], function () {
        Route::put('users/{user}/2fa', 'Users\TwoFactorController@update');
        Route::post('users/{user}/2fa/verify', 'Users\TwoFactorController@verify');
        Route::delete('users/{user}/2fa', 'Users\TwoFactorController@destroy');
    });

    Route::get('users/{user}/sessions', 'Users\SessionsController@index');

    Route::get('/sessions/{session}', 'SessionsController@show');
    Route::delete('/sessions/{session}', 'SessionsController@destroy');

    Route::apiResource('roles', 'Authorization\RolesController')->except('show');
    Route::get('/roles/{roleId}', 'Authorization\RolesController@show');

    Route::get('roles/{role}/permissions', 'Authorization\RolePermissionsController@show');
    Route::put('roles/{role}/permissions', 'Authorization\RolePermissionsController@update');

    Route::apiResource('permissions', 'Authorization\PermissionsController');

    Route::get('/settings', 'SettingsController@index');

    Route::get('/countries', 'CountriesController@index');

    // Field Activities Module API
    Route::apiResource('field-activity-expenses', '\\App\\Http\\Controllers\\Api\\FieldActivityExpenseController');
    Route::apiResource('field-activity-actual-expenses', '\\App\\Http\\Controllers\\Api\\FieldActivityActualExpenseController');
    Route::apiResource('field-activity-logistics', '\\App\\Http\\Controllers\\Api\\FieldActivityLogisticController');
    Route::apiResource('field-activity-transport-logs', '\\App\\Http\\Controllers\\Api\\FieldActivityTransportLogController');
    Route::apiResource('field-activity-timelines', '\\App\\Http\\Controllers\\Api\\FieldActivityTimelineController');
    Route::apiResource('field-activity-documents', '\\App\\Http\\Controllers\\Api\\FieldActivityDocumentController');
    Route::get('field-activity-documents/{id}/view', [\App\Http\Controllers\Api\FieldActivityDocumentController::class, 'view']);
    Route::get('field-activity-documents/{id}/download', [\App\Http\Controllers\Api\FieldActivityDocumentController::class, 'download']);
    Route::apiResource('field-activity-approvals', '\\App\\Http\\Controllers\\Api\\FieldActivityApprovalController');
    // Add custom endpoints for workflow, progress, GPS, etc. as needed
    Route::post('field-activities/{id}/logs/{date}/review',
        [FieldActivityLogController::class, 'review']);
    // Per-work-item review endpoint
    Route::post('field-activities/{id}/logs/{date}/work/{workIndex}/review',
        [FieldActivityLogController::class, 'reviewWork']);
    // Per-item review endpoint for work/expenses/logistics action buttons
    Route::post('field-activities/{id}/logs/{date}/{type}/{itemIndex}/review',
        [FieldActivityLogController::class, 'reviewItem'])
        ->where('type', 'work|expenses|logistics');
});



Route::group(['prefix' => 'api'], function () {
    Route::get('excel-data', [ExcelDataController::class, 'index']);
    Route::get('excel-data/{identifier}', [ExcelDataController::class, 'show']);
    Route::get('excel-data-lookup', [ExcelDataController::class, 'lookup']);
});

// Add search route for users
Route::get('users/search', 'Users\UsersController@search');

// ============================================================================
// 🔥 CRITICAL: BaBOT WEBHOOK ROUTES - NO AUTH REQUIRED
// ============================================================================
// These routes MUST be outside any auth middleware groups
// BaBOT calls these endpoints to send status updates and rating responses

// ============================================================================
// 🧪 DEBUG: Test endpoint to see raw webhook data
// ============================================================================
Route::post('v1/test-webhook', function(Request $request) {
    Log::info('🧪 TEST WEBHOOK RECEIVED', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'headers' => $request->headers->all(),
        'query' => $request->query(),
        'all_data' => $request->all(),
        'json_data' => $request->json()->all(),
        'raw_content' => $request->getContent(),
        'content_type' => $request->header('Content-Type'),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Test webhook received',
        'received_data' => $request->all(),
        'timestamp' => now()->toIso8601String(),
    ]);
});

// ============================================================================
// Primary webhook endpoints - /api/v1/* format (what BaBOT is calling)
// ============================================================================

// 📥 Message Status Webhook (with logging)
Route::post('v1/broadcasts/status', function(Request $request) {
    Log::info('📥 ROUTE HIT: v1/broadcasts/status', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'payload' => $request->all(),
        'raw_body' => $request->getContent(),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->messageStatus($request);
});

// ⭐ Rating Response Webhook (with enhanced logging)
Route::post('v1/ratings/responses', function(Request $request) {
    Log::info('⭐ ROUTE HIT: v1/ratings/responses', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'headers' => [
            'content-type' => $request->header('Content-Type'),
            'user-agent' => $request->header('User-Agent'),
        ],
        'payload' => $request->all(),
        'raw_body' => $request->getContent(),
        'has_request_id' => $request->has('request_id'),
        'has_phone' => $request->has('phone_number'),
        'has_rating_type' => $request->has('rating_type'),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->receiveRating($request);
});

// 🐛 Issue Submission Webhook (with logging)
Route::post('v1/issues', function(Request $request) {
    Log::info('🐛 ROUTE HIT: v1/issues', [
        'url' => $request->fullUrl(),
        'payload' => $request->all(),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->receiveIssue($request);
});

// 👁️ Announcement View Webhook (with logging)
Route::post('v1/announcements/views', function(Request $request) {
    Log::info('👁️ ROUTE HIT: v1/announcements/views', [
        'url' => $request->fullUrl(),
        'payload' => $request->all(),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->recordAnnouncementView($request);
});

// GET endpoints (no logging needed for these)
Route::get('v1/issues/status', [BotWebhookController::class, 'getIssueStatus']);
Route::get('v1/batches/stats', [BotWebhookController::class, 'getBatchStats']);

// POST endpoints
Route::post('v1/issues/update', [BotWebhookController::class, 'updateIssueStatus']);

// ============================================================================
// Legacy webhook routes - /api/bot/webhook/* format (backward compatibility)
// ============================================================================

Route::post('bot/webhook/message-status', function(Request $request) {
    Log::info('📥 LEGACY ROUTE HIT: bot/webhook/message-status', [
        'payload' => $request->all(),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->messageStatus($request);
});

Route::post('bot/webhook/rating', function(Request $request) {
    Log::info('⭐ LEGACY ROUTE HIT: bot/webhook/rating', [
        'payload' => $request->all(),
    ]);
    
    return app()->make('Vanguard\Http\Controllers\Api\BotWebhookController')
        ->receiveRating($request);
});

Route::post('bot/webhook/issue', [BotWebhookController::class, 'receiveIssue']);
Route::get('bot/webhook/issue-status', [BotWebhookController::class, 'getIssueStatus']);
Route::get('bot/webhook/batch-stats', [BotWebhookController::class, 'getBatchStats']);
Route::post('bot/webhook/issues/update', [BotWebhookController::class, 'updateIssueStatus']);
Route::post('bot/webhook/announcements/views', [BotWebhookController::class, 'recordAnnouncementView']);

// API endpoint to fetch all teams for fam.blade.php
Route::middleware(['auth:sanctum'])->get('/teams', function () {
    return response()->json(Team::all(['id', 'name']));
});

// Regions CRUD (admin/manager only)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/regions', function () {
        return response()->json(Region::with('counties')->get());
    });
    Route::get('/regions/{region}/counties', function (Region $region) {
        return response()->json($region->counties()->get(['counties.id', 'counties.name']));
    });
    Route::post('/regions', function (Request $request) {
        $user = $request->user();
        if (!$user->hasRole('Admin') && !$user->hasRole('Manager')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $region = Region::create(['name' => $request->name]);
        if ($request->counties) {
            $region->counties()->sync($request->counties);
        }
        return response()->json($region->load('counties'));
    });
    Route::put('/regions/{region}', function (Request $request, Region $region) {
        $user = $request->user();
        if (!$user->hasRole('Admin') && !$user->hasRole('Manager')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $region->update(['name' => $request->name]);
        if ($request->counties) {
            $region->counties()->sync($request->counties);
        }
        return response()->json($region->load('counties'));
    });
    Route::delete('/regions/{region}', function (Request $request, Region $region) {
        $user = $request->user();
        if (!$user->hasRole('Admin') && !$user->hasRole('Manager')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $region->delete();
        return response()->json(['success' => true]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('field-activities/{id}/invoice', 
        [\App\Http\Controllers\Api\FieldActivityInvoiceController::class, 'store']);
    Route::post('field-activities/{id}/invoice/review', 
        [\App\Http\Controllers\Api\FieldActivityInvoiceController::class, 'review']);
    Route::get('field-activities/{id}/invoice', 
        [\App\Http\Controllers\Api\FieldActivityInvoiceController::class, 'show']);
});

// Field Activity Logs API
Route::get('field-activities/{activity}/logs', [\Vanguard\Http\Controllers\Api\FieldActivityLogController::class, 'index']);
Route::post('field-activities/{activity}/logs', [\Vanguard\Http\Controllers\Api\FieldActivityLogController::class, 'store']);

// Admin status update endpoint
Route::post('field-activities/{activity}/set-status', [\App\Http\Controllers\Api\FieldActivityController::class, 'setStatus']);