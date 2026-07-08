
<?php
/**
 * Authentication
 */

use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Web\PaymentController;
use Vanguard\Http\Controllers\Web\AppraisalController;
use Vanguard\Http\Controllers\Web\ApprovalController;
use Vanguard\Http\Controllers\Web\AssignSubordinatesController;
use Vanguard\Http\Controllers\Web\ContractController;
use Vanguard\Http\Controllers\Web\ContractDashboardController;
use Vanguard\Http\Controllers\Web\OnboardingController;
use Vanguard\Http\Controllers\Web\Profile\ProfileController;
use Vanguard\Http\Controllers\Web\MismatchedPaymentController;
use Vanguard\Http\Controllers\Web\SignaturePadController;
use Vanguard\Http\Controllers\Web\Users\UsersController;
use Vanguard\Http\Controllers\Web\Messages\MessageController;
use Vanguard\Http\Controllers\Web\Messages\ContactsImportController;
use Vanguard\Http\Controllers\Web\Profile\AvatarController;
use Vanguard\Http\Controllers\Web\Profile\LoginDetailsController as ProfileLoginDetailsController;
use Vanguard\Http\Controllers\Web\Profile\SensitiveInfoController as ProfileSensitiveInfoController;
use Vanguard\Http\Controllers\Web\RecommendationCertificateController;
use Vanguard\Http\Controllers\Web\ReportWizardController;
use Vanguard\Http\Controllers\Web\Users\DetailsController;
use Vanguard\Http\Controllers\Web\Users\LoginDetailsController;
use Vanguard\Http\Controllers\Web\Users\SessionsController;
use Vanguard\Http\Controllers\Web\Emails\EmailController;
use Vanguard\Http\Controllers\Web\Emails\EmailImportController;
use Vanguard\Http\Controllers\Web\Group\GroupController;
use Vanguard\Http\Controllers\Web\Projects\ProjectsController;
use Vanguard\Http\Controllers\Web\Support\SupportIssueController;
use Vanguard\Http\Controllers\Web\Support\SupportCategoryController;
use Vanguard\Http\Controllers\Web\Users\SensitiveInfoController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Vanguard\Http\Controllers\Web\Assets\AssetController;
use Vanguard\Http\Controllers\Web\Assets\AssetAssignmentController;
use Vanguard\Http\Controllers\Web\Assets\AssetDistributionController;
use Vanguard\Http\Controllers\Web\RegionController;

// Field Activities Page
use Vanguard\Http\Controllers\Web\Assets\AssetHistoryController;
use Vanguard\Http\Controllers\Web\Assets\AssetReturnController;
use Vanguard\Http\Controllers\Web\Assets\MyAssetsController;
use Vanguard\Http\Controllers\Web\FieldReportController;
use Vanguard\Http\Controllers\Web\GeneralReportController;
use Vanguard\Http\Controllers\Web\BackToOfficeReportController;
use Vanguard\Http\Controllers\Web\InvoicePaymentController;
use Vanguard\Http\Controllers\Web\RateableItemController;
use Vanguard\Http\Controllers\Web\TrainingEventController;
use Vanguard\Http\Controllers\Web\UserContractController;
use Vanguard\Http\Controllers\Web\UserDocumentController;
use Vanguard\Http\Controllers\Web\ComplianceController;
use Vanguard\Http\Controllers\Web\DocumentAcknowledgementController;
use Vanguard\Http\Controllers\Web\Users\BankDetailsController;
use Vanguard\Http\Controllers\Web\Visualization\ExcelController;
use Vanguard\Http\Controllers\Web\Visualization\VisualizationController;
use Vanguard\Http\Controllers\Auth\ChangePasswordController;
use Vanguard\Onboarding;
use Vanguard\Http\Controllers\Web\BotAdminController;
use Vanguard\Http\Controllers\Web\FieldActivitiesController;
use Vanguard\County;
use Vanguard\Http\Controllers\Web\NdaController;
use Vanguard\Http\Controllers\Web\PolicyAcknowledgementController;
use Vanguard\Http\Controllers\Web\MeetingController;
use App\Http\Controllers\Web\CoachRequisitionController;

Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::group(['middleware' => ['registration', 'guest']], function () {
    Route::get('register', 'Auth\RegisterController@show');
    Route::post('register', 'Auth\RegisterController@register');
});



Route::emailVerification();

Route::get('storage/upload/users/{filename}', function ($filename) {
    $path = Storage::disk('avatars')->path($filename);

    if (!Storage::disk('avatars')->exists($filename)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

Route::group(['middleware' => ['password-reset', 'guest']], function () {
    Route::resetPassword();
});

/**
 * Two-Factor Authentication
 */
Route::group(['middleware' => 'two-factor'], function () {
    Route::get('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@show')->name('auth.token');
    Route::post('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@update')->name('auth.token.validate');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    // Employee document uploads
    Route::post('/users/{user}/education-documents', [UserDocumentController::class, 'uploadEducation'])->name('user.education.upload');
    Route::post('/users/{user}/other-documents', [UserDocumentController::class, 'uploadOther'])->name('user.otherdocs.upload');

    // HR Policy Acknowledgement
    Route::middleware(['nda'])->group(function () {
        Route::get('/policy-acknowledgement', [PolicyAcknowledgementController::class, 'show'])->name('policy.acknowledgement');
        Route::post('/policy-acknowledgement', [PolicyAcknowledgementController::class, 'acknowledge'])->name('policy.acknowledge');

        Route::prefix('document-acknowledgements')->name('document_acknowledgements.')->group(function () {
            Route::get('/', [DocumentAcknowledgementController::class, 'userAssignments'])->name('assignments.index');
            Route::get('/assignments/{assignment}', [DocumentAcknowledgementController::class, 'showAssignment'])->name('assignments.show');
            Route::post('/assignments/{assignment}/acknowledge', [DocumentAcknowledgementController::class, 'acknowledge'])->name('assignments.acknowledge');
            Route::get('/assignments/{assignment}/download', [DocumentAcknowledgementController::class, 'downloadAssignment'])->name('assignments.download');
        });
    });

Route::prefix('coach-requisitions')->name('coach-requisitions.')->group(function () {
    Route::get('/', [CoachRequisitionController::class, 'index'])->name('index');
    Route::get('create', [CoachRequisitionController::class, 'create'])->name('create');
    Route::post('/', [CoachRequisitionController::class, 'store'])->name('store');
    Route::get('{id}/edit', [CoachRequisitionController::class, 'edit'])->name('edit');
    Route::put('{id}', [CoachRequisitionController::class, 'update'])->name('update');
    Route::get('{id}', [CoachRequisitionController::class, 'show'])->name('show');
    Route::get('{id}/approval', [CoachRequisitionController::class, 'approval'])->name('approval');
    Route::post('{id}/approval', [CoachRequisitionController::class, 'approvalAction'])->name('approval.action');
});
// Field activity invoice PDF (server-side)
Route::get('field-activities/{id}/invoice.pdf', [\App\Http\Controllers\Web\FieldActivityController::class, 'invoicePdf'])
    ->name('field-activities.invoice.pdf')
    ->middleware('auth');
    // Two-factor authentication routes
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
        ->name('two-factor.enable');
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
        ->name('two-factor.disable');
    Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
        ->name('two-factor.qr-code');
    Route::get('/user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
        ->name('two-factor.secret-key');
    Route::post('/user/confirmed-two-factor-authentication', [TwoFactorAuthenticationController::class, 'update'])
        ->name('two-factor.confirm');
});
/**
 * Social Login
 */
Route::get('auth/{provider}/login', 'Auth\SocialAuthController@redirectToProvider')->name('social.login');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

/**
 * Impersonate Routes
 */
Route::group(['middleware' => 'auth'], function () {
    Route::impersonate();
});


// Add employeeinfo middleware after nda
Route::group(['middleware' => ['auth', 'verified', 'check.onboarding', 'nda', 'employeeinfo']], function () {



        


    /**
     * Dashboard
     */
    Route::get('/', 'DashboardController@index')
    ->name('dashboard');
    /**
     * User Profile
     */
    // Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
    //     Route::get('/', 'ProfileController@show')->name('profile');
    //     Route::get('activity', 'ActivityController@show')->name('profile.activity');
    //     Route::put('details', 'DetailsController@update')->name('profile.update.details');

    //     Route::post('avatar', 'AvatarController@update')->name('profile.update.avatar');
    //     Route::post('avatar/external', 'AvatarController@updateExternal')
    //         ->name('profile.update.avatar-external');

    //     Route::put('login-details', 'LoginDetailsController@update')
    //         ->name('profile.update.login-details');

    //     Route::get('sessions', 'SessionsController@index')
    //         ->name('profile.sessions')
    //         ->middleware('session.database');

    //     Route::delete('sessions/{session}/invalidate', 'SessionsController@destroy')
    //         ->name('profile.sessions.invalidate')
    //         ->middleware('session.database');

    //     // New route for updating subordinate assignments
    //     Route::post('update-assignments', 'ProfileController@updateAssignments')
    //         ->name('profile.update-assignments');
    // });


    Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => ['auth', 'verified']], function () {
        // Profile view
        Route::get('/', [ProfileController::class, 'show'])->name('profile');
        Route::put('details', [ProfileController::class, 'update'])->name('profile.update.details');

        // Profile updates
        Route::put('login-details', [ProfileLoginDetailsController::class, 'update'])->name('profile.update.login-details');

        // Avatar updates
        Route::post('avatar', [AvatarController::class, 'update'])->name('profile.update.avatar');
        Route::post('avatar/external', [AvatarController::class, 'updateExternal'])->name('profile.update.avatar-external');

        // Sessions
        Route::get('sessions', [SessionsController::class, 'index'])
            ->name('profile.sessions')
            ->middleware('session.database');
        Route::delete('sessions/{session}/invalidate', [SessionsController::class, 'destroy'])
            ->name('profile.sessions.invalidate')
            ->middleware('session.database');
        // Add this with your other profile routes
        Route::put('sensitive-info', [ProfileSensitiveInfoController::class, 'updateSensitiveInfo'])
            ->name('profile.update.sensitive-info')
            ->middleware('permission:sensitive.information.manage');

        Route::get('bank-branches', [OnboardingController::class, 'getBankBranches'])
            ->name('get-bank-branches')
            ->middleware('permission:sensitive.information.manage');
        // Assignments (if this is still needed for the profile)
        Route::post('update-assignments', [ProfileController::class, 'updateAssignments'])
            ->name('profile.update-assignments');

        // Geo Boundary Ajax Requests
        Route::get('subcounties', [ProfileController::class, 'getSubcounties'])->name('profile.get.subcounties');
        Route::get('wards', [ProfileController::class, 'getWards'])->name('profile.get.wards');

        // Employee Info update
        Route::post('employeeinfo', [\Vanguard\Http\Controllers\Web\Profile\EmployeeInfoController::class, 'update'])->name('profile.update.employeeinfo');
    });

    ##Change Password
//     Route::middleware(['auth'])->group(function () {
//      Route::get('/change-password', [ChangePasswordController::class, 'showForceChangeForm'])
//         ->name('password.force.change');

//     Route::post('/change-password', [ChangePasswordController::class, 'forceUpdate'])
//         ->name('password.force.update');
//    });

    /**
     * Two-Factor Authentication Setup
     */

    Route::group(['middleware' => 'two-factor'], function () {
        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('two-factor.enable');

        Route::get('two-factor/verification', 'TwoFactorController@verification')
            ->name('two-factor.verification')
            ->middleware('verify-2fa-code');

        Route::post('two-factor/verify', 'TwoFactorController@verify')
            ->name('two-factor.verify')
            ->middleware('verify-2fa-code');

        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('two-factor.disable');
    });

    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User Management Routes
        Route::group([
            'prefix' => 'users',
            'middleware' => 'permission:users.manage'
        ], function () {
            // Main user routes
Route::get('/', [UsersController::class, 'index'])->name('users.index');
Route::get('/create', [UsersController::class, 'create'])->name('users.create');
Route::post('/', [UsersController::class, 'store'])->name('users.store');

// ✅ Location routes FIRST - before any /{user} wildcard
Route::get('/get-subcounties', [UsersController::class, 'getSubcounties'])
    ->name('get.subcounties');
Route::get('/get-wards', [UsersController::class, 'getWards'])
    ->name('get.wards');

// Search & import (also before wildcard)
Route::get('/search', [UsersController::class, 'search'])->name('users.search');
Route::get('/deep-search', [UsersController::class, 'deepSearch'])->name('users.deepSearch');
Route::get('/list', [UsersController::class, 'list'])->name('users.list');
Route::post('/import', [UsersController::class, 'importUsers'])->name('users.import');
Route::get('/template/download', function () {
    $file = public_path('templates/user_template.xlsx');
    if (file_exists($file)) {
        return response()->download($file);
    }
    return redirect()->back()->withErrors('Template file not found.');
})->name('users.downloadTemplate');

// Wildcard /{user} routes AFTER
Route::get('/{user}', [UsersController::class, 'view'])->name('users.view');
Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::put('/{user}', [UsersController::class, 'update'])->name('users.update');
Route::delete('/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

// User details updates
Route::put('/{user}/update/details', [DetailsController::class, 'update'])
    ->name('users.update.details');
Route::put('/{user}/update/login-details', [LoginDetailsController::class, 'update'])
    ->name('users.update.login-details');
Route::put('/{user}/update/counties', [DetailsController::class, 'updateCounties'])
    ->name('users.update.counties')
    ->middleware('permission:users.update.counties');

// Avatar management
Route::post('/{user}/update/avatar', [AvatarController::class, 'update'])
    ->name('user.update.avatar');
Route::post('/{user}/update/avatar/external', [AvatarController::class, 'updateExternal'])
    ->name('user.update.avatar.external');

// Sensitive info and bank details
Route::put('/{user}/sensitive-info', [SensitiveInfoController::class, 'updateSensitiveInfo'])
    ->name('users.update.sensitive-info');
Route::put('/{user}/bank-details', [BankDetailsController::class, 'updateBankDetails'])
    ->name('users.update.bank-details');

            // Session management (if using database sessions)
            Route::middleware('session.database')->group(function () {
                Route::get('/{user}/sessions', [SessionsController::class, 'index'])
                    ->name('user.sessions');
                Route::delete('/{user}/sessions/{session}/invalidate', [SessionsController::class, 'destroy'])
                    ->name('user.sessions.invalidate');
            });

            // Team management routes
            Route::delete('/unassign-field-officer/{fieldOfficer}', [AssignSubordinatesController::class, 'unassignFieldOfficer'])
                ->name('assign-subordinates.unassign-field-officer');
        });
    });

    Route::put('contracts/{id}/terminate', 'ContractController@terminate')->name('contracts.terminate');
    Route::put('contracts/{id}/reset', 'ContractController@reset')->name('contracts.reset');


    Route::get('profile/subcounties', [ProfileController::class, 'getSubcounties'])->name('profile.get.subcounties');
    Route::get('profile/wards', [ProfileController::class, 'getWards'])->name('profile.get.wards');
    Route::get('/users/template/download', function () {
        $file = public_path('templates/user_template.xlsx');

        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return redirect()->back()->withErrors('Template file not found.');
        }
    })->name('users.downloadTemplate');
    // Route::get('/get-subordinates', [ProfileController::class, 'getSubordinates'])->name('get.subordinates');


    /**
     * Assets Management
     */
    Route::middleware(['auth'])->group(function () {
        // User list
        Route::get('/users/list', [UsersController::class, 'list'])->name('users.list');

        // Asset management
        Route::prefix('asset')->name('asset.')->middleware('permission:assets.view')->group(function () {
            Route::get('/', [AssetController::class, 'index'])->name('index');
            Route::get('/create', [AssetController::class, 'create'])->name('create');
            Route::post('/', [AssetController::class, 'store'])->name('store');
            Route::get('/{asset}', [AssetController::class, 'show'])->name('show');
            Route::get('/{asset}/edit', [AssetController::class, 'edit'])->name('edit');
            Route::put('/{asset}', [AssetController::class, 'update'])->name('update');
            Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('destroy');
        });
 
        // Asset assignment
        Route::prefix('asset-assignment')->name('asset.assignment.')->middleware('permission:assets.assign')->group(function () {
            Route::get('/', [AssetAssignmentController::class, 'index'])->name('index');
            Route::get('/{user}', [AssetAssignmentController::class, 'showAssignmentForm'])->name('form');
            Route::post('/{user}/assign', [AssetAssignmentController::class, 'assign'])->name('assign');
            Route::post('/return/{assignment}', [AssetAssignmentController::class, 'returnAsset'])->name('return');
        });

        // Asset distribution
        Route::prefix('asset-distribution')->name('asset.distribution.')->middleware('permission:assets.bulk_assign')->group(function () {
            Route::get('/', [AssetDistributionController::class, 'index'])->name('index');
            Route::get('/{user}', [AssetDistributionController::class, 'showDistributionForm'])->name('form');
            Route::post('/{user}', [AssetDistributionController::class, 'processDistribution'])->name('process');
            Route::post('/{user}/return', [AssetDistributionController::class, 'processReturn'])->name('return');
        });

        // Asset history
        Route::prefix('asset-history')->name('asset.history.')->middleware('permission:assets.view')->group(function () {
            Route::get('/', [AssetHistoryController::class, 'index'])->name('index');
            Route::patch('/{assignment}/update-numbers', [AssetHistoryController::class, 'updateSerialNumber'])
                ->name('update-numbers')
                ->middleware('permission:assets.manage');
            Route::get('/export', [AssetHistoryController::class, 'export'])
                ->name('export')
                ->middleware('permission:assets.export');
        });

        // My assets
        Route::get('/my-assets', [MyAssetsController::class, 'index'])->name('my-assets');

        // Helper routes
        Route::get('/assignable-users', [AssetController::class, 'getAssignableUsers'])->name('assets.assignable-users');
        Route::get('/distributable-users', [AssetController::class, 'getDistributableUsers'])->name('assets.distributable-users');

        Route::prefix('compliance')->name('compliance.')->middleware('permission:compliance.view')->group(function () {
            Route::get('/', [ComplianceController::class, 'index'])->name('index');
            Route::get('/create', [ComplianceController::class, 'create'])->name('create')->middleware('permission:compliance.create');
            Route::post('/', [ComplianceController::class, 'store'])->name('store')->middleware('permission:compliance.create');

            Route::prefix('document-acknowledgements')->name('document_acknowledgements.')->middleware('permission:compliance.view')->group(function () {
                Route::get('/', [DocumentAcknowledgementController::class, 'index'])->name('index');
                Route::get('/create', [DocumentAcknowledgementController::class, 'create'])->name('create')->middleware('permission:compliance.create');
                Route::post('/', [DocumentAcknowledgementController::class, 'store'])->name('store')->middleware('permission:compliance.create');
                Route::get('/{document_acknowledgement}', [DocumentAcknowledgementController::class, 'show'])->name('show');
                Route::get('/{document_acknowledgement}/edit', [DocumentAcknowledgementController::class, 'edit'])->name('edit')->middleware('permission:compliance.edit');
                Route::put('/{document_acknowledgement}', [DocumentAcknowledgementController::class, 'update'])->name('update')->middleware('permission:compliance.edit');
                Route::get('/{document_acknowledgement}/download', [DocumentAcknowledgementController::class, 'download'])->name('download');
                Route::post('/{document_acknowledgement}/assign', [DocumentAcknowledgementController::class, 'assign'])->name('assign')->middleware('permission:compliance.edit');
            });

            Route::get('/{compliance}', [ComplianceController::class, 'show'])->name('show');
            Route::get('/{compliance}/edit', [ComplianceController::class, 'edit'])->name('edit')->middleware('permission:compliance.edit');
            Route::put('/{compliance}', [ComplianceController::class, 'update'])->name('update')->middleware('permission:compliance.edit');
            Route::delete('/{compliance}', [ComplianceController::class, 'destroy'])->name('destroy')->middleware('permission:compliance.delete');
            Route::get('/{compliance}/download', [ComplianceController::class, 'download'])->name('download');
            Route::post('/{compliance}/renew', [ComplianceController::class, 'renew'])->name('renew')->middleware('permission:compliance.renew');
        });
        Route::post('/compliance/categories', [ComplianceController::class, 'storeCategory'])->name('compliance.categories.store')->middleware('permission:compliance.create');
    });
    /**
     * Messages
     */
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/my-messages', [MessageController::class, 'userMessages'])->name('messages.user');
    Route::get('/messages/send-bulk', [MessageController::class, 'showSendBulkSms'])->name('messages.send_bulk');
    Route::get('/messages/send-single', [MessageController::class, 'showSendSingleSms'])->name('messages.send_single');
    Route::get('/messages/send-select', [MessageController::class, 'showSendSelectSms'])->name('messages.send_select');
    Route::get('/messages/templates', [MessageController::class, 'showMessageTemplates'])->name('messages.templates');
    Route::get('/messages/filter-users', [MessageController::class, 'filterUsers'])->name('messages.filter_users');
    Route::get('/messages/batch/{batch_number}', [MessageController::class, 'viewBatch'])->name('messages.view_batch');
    Route::get('/messages/{id}/view', [MessageController::class, 'viewSingle'])->name('messages.view_single');
    Route::get('/contacts/download-template', [ContactsImportController::class, 'downloadTemplate'])->name('contacts.download_template');

    Route::post('/messages/send-bulk', [MessageController::class, 'sendBulkSms'])->name('messages.send_bulk');
    Route::post('/messages/send-single', [MessageController::class, 'sendSingleSms'])->name('messages.send_single');
    Route::post('/messages/send-select', [MessageController::class, 'sendSelectSms'])->name('messages.send_select');
    Route::post('/contacts/import', [ContactsImportController::class, 'import'])->name('contacts.import');

    /**
     * Emails
     */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
        Route::get('/emails/send-bulk', [EmailController::class, 'showSendBulkEmail'])->name('emails.send_bulk');
        Route::get('/emails/send-single', [EmailController::class, 'showSendSingleEmail'])->name('emails.send_single');
        Route::get('/emails/send-select', [EmailController::class, 'showSendSelectEmail'])->name('emails.send_select');
        Route::get('/emails/filter-users', [EmailController::class, 'filterUsers'])->name('emails.filter_users');
        Route::get('/emails/batch/{batch_number}', [EmailController::class, 'viewBatch'])->name('emails.view_batch');
        Route::get('/emails/{id}/view', [EmailController::class, 'viewSingle'])->name('emails.view_single');
        Route::get('/emails/{id}/download', [EmailController::class, 'downloadEmail'])->name('emails.download_email');
        Route::post('/emails/send-bulk', [EmailController::class, 'sendBulkEmail'])->name('emails.send_bulk');
        Route::post('/emails/send-select', [EmailController::class, 'sendSelectEmail'])->name('emails.send_select');
        Route::post('/emails/import', [EmailController::class, 'import'])->name('emails.import');
        Route::get('/emails/import', [EmailImportController::class, 'showImportForm'])->name('emails.import');
        Route::post('/emails/import', [EmailImportController::class, 'import'])->name('emails.import.submit');
        Route::get('/emails/download-template', [EmailImportController::class, 'downloadTemplate'])->name('emails.download_template');
        Route::get('/emails/template/download', function () {
            $file = public_path('templates/email_import_template.xlsx');

            if (file_exists($file)) {
                return response()->download($file);
            } else {
                return redirect()->back()->withErrors('Template file not found.');
            }
        })->name('emails.download_template');
    });
    Route::get('emails/{email}/edit', [EmailImportController::class, 'edit'])->name('emails.edit');
    Route::put('emails/{email}', [EmailImportController::class, 'update'])->name('emails.update');
    Route::delete('emails/{email}', [EmailImportController::class, 'destroy'])->name('emails.destroy');

    /**
     * Group
     */
    Route::resource('groups', GroupController::class);
    //Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::delete('emails/{email}', [EmailController::class, 'destroy'])->name('emails.destroy');
    Route::get('/download-template/{type}', [GroupController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::get('/groups/search', [GroupController::class, 'search'])->name('groups.search');
    // Route::resource('contacts', 'ContactController');
    Route::get('contacts/{contact}/edit', [ContactsImportController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}', [ContactsImportController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactsImportController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/{group}/members/create', [GroupController::class, 'createMember'])->name('groups.addMember');
    Route::post('/{group}/members', [GroupController::class, 'storeMember'])->name('groups.storeMember');
    Route::get('/groups/{group}/members/{member}/edit', [GroupController::class, 'editMember'])->name('groups.editMember');
    Route::put('/groups/{group}/members/{member}', [GroupController::class, 'updateMember'])->name('groups.updateMember');
    Route::delete('/groups/{group}/members/{member}', [GroupController::class, 'destroyMember'])->name('groups.destroyMember');
    /**
     * Support
     */
    Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'support'], function () {

        // Support Issues Index for Users
        Route::get('/', [SupportIssueController::class, 'index'])->name('support.index');

        // Manage Issues View for Admins
        Route::get('/manage', [SupportIssueController::class, 'manage'])->name('support.manage');

        // Raise Issue
        Route::get('/create', [SupportIssueController::class, 'create'])->name('support.create');
        Route::post('/create', [SupportIssueController::class, 'store'])->name('support.store');

        Route::get('/manage/{issue}', [SupportIssueController::class, 'manageShow'])->name('support.manage_show');

        Route::get('/user/{issue}', [SupportIssueController::class, 'userShow'])->name('support.user_show');

        // Route::post('/categories/store', [SupportCategoryController::class, 'store'])->name('support.categories.store');

        Route::post('/support/categories/store', [SupportCategoryController::class, 'store'])->name('support.categories.store');
        Route::put('/categories/{category}', [SupportCategoryController::class, 'update'])->name('support.categories.update');
        Route::delete('/categories/{category}', [SupportCategoryController::class, 'destroy'])->name('support.categories.destroy');

        // Route::get('/', [SupportCategoryController::class, 'index'])->name('support.categories.index');
        // Route::post('/', [SupportCategoryController::class, 'store'])->name('support.categories.store');
        // Route::get('/create', [SupportCategoryController::class, 'create'])->name('support.categories.create');
        // Route::get('/{category}/edit', [SupportCategoryController::class, 'edit'])->name('support.categories.edit');
        // Route::put('/{category}', [SupportCategoryController::class, 'update'])->name('support.categories.update');
        // Route::delete('/{category}', [SupportCategoryController::class, 'destroy'])->name('support.categories.destroy');


        // Comment on an issue
        Route::post('/{issue}/comment', [SupportIssueController::class, 'storeComment'])->name('support.comment.store');

        // Update issue status for Admins
        Route::post('/{issue}/status', [SupportIssueController::class, 'updateStatus'])->name('support.update_status');

        // Close an issue
        Route::post('/close/{issue}', [SupportIssueController::class, 'close'])->name('support.close');

        // Incidence Submission
        Route::get('/incidence', [SupportIssueController::class, 'incidence'])->name('support.incidence');
        Route::post('/incidence', [SupportIssueController::class, 'submitIncidence'])->name('support.incidence.submit');

        // System Issue Submission
        Route::get('/system_issue', [SupportIssueController::class, 'systemIssue'])->name('support.system_issue');
        Route::post('/system_issue', [SupportIssueController::class, 'submitSystemIssue'])->name('support.system_issue.submit');

        // Payment Issue Submission
        Route::get('/payment_issue', [SupportIssueController::class, 'paymentIssue'])->name('support.payment_issue');
        Route::post('/payment_issue', [SupportIssueController::class, 'submitPaymentIssue'])->name('support.payment_issue.submit');

        // Contracting Issue Submission
        Route::get('/contracting_issue', [SupportIssueController::class, 'contractingIssue'])->name('support.contracting_issue');
        Route::post('/contracting_issue', [SupportIssueController::class, 'submitContractingIssue'])->name('support.contracting_issue.submit');
        Route::post('/support/{issue}/escalate', [SupportIssueController::class, 'escalate'])->name('support.escalate');
    });



    Route::prefix('visualizations')->group(function () {
        Route::get('/', [VisualizationController::class, 'index'])->name('visualizations.index');
        Route::post('/upload', [VisualizationController::class, 'upload'])->name('upload.excel');
        Route::delete('/delete-file', [VisualizationController::class, 'deleteFile'])->name('delete.file');
    });

    Route::post('/upload-excel', [ExcelController::class, 'upload'])->name('upload.excel');
    Route::delete('/delete-selected', [ExcelController::class, 'deleteSelected'])->name('delete.selected');
    /**
     * Roles & Permissions
     */
    Route::group(['namespace' => 'Authorization'], function () {
        Route::resource('roles', 'RolesController')->except('show')->middleware('permission:roles.manage');
        Route::post('permissions/save', 'RolePermissionsController@update')
            ->name('permissions.save')
            ->middleware('permission:permissions.manage');

        Route::resource('permissions', 'PermissionsController')->middleware('permission:permissions.manage');
    });

    /**
     * Settings
     */
    Route::get('settings', 'SettingsController@general')->name('settings.general')
        ->middleware('permission:settings.general');

    Route::post('settings/general', 'SettingsController@update')->name('settings.general.update')
        ->middleware('permission:settings.general');

    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth', 'SettingsController@update')->name('settings.auth.update')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/2fa/enable', 'SettingsController@enableTwoFactor')
        ->name('settings.auth.2fa.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/2fa/disable', 'SettingsController@disableTwoFactor')
        ->name('settings.auth.2fa.disable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/enable', 'SettingsController@enableCaptcha')
        ->name('settings.registration.captcha.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/disable', 'SettingsController@disableCaptcha')
        ->name('settings.registration.captcha.disable')
        ->middleware('permission:settings.auth');

    Route::get('settings/notifications', 'SettingsController@notifications')
        ->name('settings.notifications')
        ->middleware('permission:settings.notifications');

    Route::post('settings/notifications', 'SettingsController@update')
        ->name('settings.notifications.update')
        ->middleware('permission:settings.notifications');

    /**
     * Activity Log
     */
    Route::get('activity', 'ActivityController@index')->name('activity.index')
        ->middleware('permission:users.activity');

    Route::get('activity/user/{user}/log', 'Users\ActivityController@index')->name('activity.user')
        ->middleware('permission:users.activity');
});

/**
 * Installation
 */
Route::group(['prefix' => 'install'], function () {
    Route::get('/', 'InstallController@index')->name('install.start');
    Route::get('requirements', 'InstallController@requirements')->name('install.requirements');
    Route::get('permissions', 'InstallController@permissions')->name('install.permissions');
    Route::get('database', 'InstallController@databaseInfo')->name('install.database');
    Route::get('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('install-app', 'InstallController@install')->name('install.install');
    Route::get('complete', 'InstallController@complete')->name('install.complete');
    Route::get('error', 'InstallController@error')->name('install.error');
});


// // Admin routes for contract setup
// Route::prefix('admin')->middleware('admin')->group(function () {
//     Route::get('contract-setup', [OnboardingController::class, 'adminContractSetup'])->name('admin.contract-setup');
//     Route::post('contract-setup', [OnboardingController::class, 'storeAdminContract'])->name('admin.store-contract');
// });

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'onboarding'], function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::get('{step}', [OnboardingController::class, 'navigate'])
        ->name('onboarding.navigate')
        ->where('step', implode('|', [
            'welcome',
            'personal-info',
            'banking-details',
            'documents',
            'policy-agreement',
            'contract',
            'final-confirmation'
        ]));

    Route::post('{step}', [OnboardingController::class, 'storeStep'])
        ->name('onboarding.store')
        ->where('step', implode('|', [
            'personal-info',
            'banking-details',
            'documents',
            'policy-agreement',
            'contract',
            'final-confirmation'
        ]));

    Route::post('restart', [OnboardingController::class, 'restart'])->name('onboarding.restart');
    Route::get('final-confirmation', [OnboardingController::class, 'finalConfirmation'])->name('onboarding.final-confirmation');
    Route::post('complete', [OnboardingController::class, 'completeFinalConfirmation'])->name('onboarding.complete');
    Route::get('get-bank-branches', [OnboardingController::class, 'getBankBranches'])->name('get-bank-branches');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    // Contracts Management
    Route::group(['prefix' => 'contracts', 'middleware' => 'permission:contracts.manage'], function () {
        Route::resource('contracts', 'ContractController');
        Route::get('/contracts/{contract}/audit-trail', [ContractController::class, 'auditTrail'])
            ->name('contracts.audit-trail');
        Route::get('/contracts/{contract}/versions/{version}/preview', [ContractController::class, 'previewVersion'])
            ->name('contracts.preview-version');
        Route::get('/contracts/{contract}/versions/{version}/download', [ContractController::class, 'downloadVersion'])
            ->name('contracts.download-version');
        Route::get('info/{id}', 'ContractController@getInfo')->name('contracts.info');
        Route::put('{id}/terminate', 'ContractController@terminate')
            ->name('contracts.terminate');
    });

    // Contracts Dashboard
    Route::group(['prefix' => 'contracts/dashboard', 'middleware' => 'permission:contracts.manage'], function () {
        Route::get('/', [ContractDashboardController::class, 'index'])->name('contracts.dashboard');
        Route::post('/export/{type}', [ContractDashboardController::class, 'export'])->name('contracts.dashboard.export');
    });

    // Contracts List - show assigned contracts
    Route::get('contractsList', [ContractController::class, 'contractsList'])
        ->name('contractsList.index')
        ->middleware('permission:contracts.list');
    Route::get('contractsList/view/{id}', [ContractController::class, 'viewContract'])
        ->name('contractsList.view')
        ->middleware('permission:contracts.manage');
    Route::put('contracts/{id}/reset', 'ContractController@reset')->name('contracts.reset');
    // Contract Approval
    Route::group(['prefix' => 'approval', 'middleware' => 'permission:contracts.approval'], function () {
        Route::get('/', 'ApprovalController@index')->name('approval.index');
        Route::get('{userId}', 'ApprovalController@show')->name('approval.show');
        Route::post('{user}/process', 'ApprovalController@process')->name('approval.process');
        Route::get('test-email', [ApprovalController::class, 'testEmail']);
    });

    // User Contract
    Route::group(['prefix' => 'user/contract'], function () {
        Route::get('/', [UserContractController::class, 'index'])->name('contract.index');
        Route::get('view', 'ContractController@viewUserContract')->name('user.contract.view');
    });

    // Appraisals
    Route::group(['prefix' => 'appraisals'], function () {
        Route::resource('appraisals', 'AppraisalController');
        Route::get('history/{user}', [AppraisalController::class, 'history'])
            ->name('appraisals.history');
        Route::get('create/{user}', [AppraisalController::class, 'create'])
            ->name('appraisals.create');
        Route::post('{user}', [AppraisalController::class, 'store'])
            ->name('appraisals.store');
        Route::post('{user}/generate', [AppraisalController::class, 'generate'])
            ->name('appraisals.generate');
    });

    // Recommendation Certificates
    Route::group([
        'prefix' => 'recommendation_certificates',
        'middleware' => 'permission:manage.recommendation'
    ], function () {
        Route::get('/', [RecommendationCertificateController::class, 'index'])
            ->name('recommendation_certificates.index');
        Route::get('create', [RecommendationCertificateController::class, 'create'])
            ->name('recommendation_certificates.create');
        Route::post('/', [RecommendationCertificateController::class, 'store'])
            ->name('recommendation_certificates.store');
        Route::get('{id}/edit', [RecommendationCertificateController::class, 'edit'])
            ->name('recommendation_certificates.edit');
        Route::put('{id}', [RecommendationCertificateController::class, 'update'])
            ->name('recommendation_certificates.update');
        Route::delete('{id}', [RecommendationCertificateController::class, 'destroy'])
            ->name('recommendation_certificates.destroy');
    });
});



Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::prefix('projects')->name('projects.')->group(function () {
        
        Route::get('/', [ProjectsController::class, 'index'])->name('index');
        Route::get('/create', [ProjectsController::class, 'create'])->name('create')
            ->middleware('permission:project.manage');  
        Route::post('/', [ProjectsController::class, 'store'])->name('store')
            ->middleware('permission:project.manage');  

        Route::get('/{project}/assign-users', [ProjectsController::class, 'assignUsers'])
            ->name('assign-users')
            ->middleware('permission:project.manage');
        Route::post('/{project}/assign-users', [ProjectsController::class, 'storeUserAssignments'])
            ->name('store-assignments')
            ->middleware('permission:project.manage');
        Route::delete('/{project}/users/{user}', [ProjectsController::class, 'removeUser'])
            ->name('remove-user')
            ->middleware('permission:project.manage');
        Route::get('/{project}/search-users', [ProjectsController::class, 'searchUsers'])
            ->name('search-users')
            ->middleware('permission:project.manage');
        
       
        Route::put('/{project}/set-active', [ProjectsController::class, 'setActive'])
            ->name('set-active');
        Route::delete('/clear-active', [ProjectsController::class, 'clearActive'])
            ->name('clear-active');
    });
});

// 
// Route::group(['middleware' => ['auth', 'verified']], function () {
    // Route::prefix('projects')->name('projects.')->group(function () {
    //    
        // Route::get('/', [ProjectsController::class, 'index'])->name('index');
        // 
    // 
        // Route::get('/create', [ProjectsController::class, 'create'])->name('create');
        // Route::post('/', [ProjectsController::class, 'store'])->name('store');
        // 
    //    
        // Route::put('/{project}/set-active', [ProjectsController::class, 'setActive'])
            // ->name('set-active');
        // Route::delete('/clear-active', [ProjectsController::class, 'clearActive'])
            // ->name('clear-active');
    // });
// });






Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assign-subordinates', [AssignSubordinatesController::class, 'index'])->name('assign-subordinates.index');
    Route::get('/get-field-officers', [AssignSubordinatesController::class, 'getFieldOfficers'])->name('get-field-officers');
    Route::post('/assign-subordinates/assign-field-officers', [AssignSubordinatesController::class, 'assignFieldOfficers'])->name('assign-subordinates.assign-field-officers');
    Route::delete('assign-subordinates/unassign-field-officer/{id}', [AssignSubordinatesController::class, 'unassignFieldOfficer'])->name('assign-subordinates.unassign-field-officer');


    Route::get('/reports', [ReportWizardController::class, 'index'])->name('report-wizard.index')->middleware('permission:view.reports');
    Route::post('/reports/generate', [ReportWizardController::class, 'generateReport'])->name('report-wizard.generate');
});


Route::group(['middleware' => ['auth', 'verified']], function () {
    // Non-wildcard routes first
    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payments.index');
    Route::get('/payments/mismatched', [PaymentController::class, 'showMismatched'])
        ->name('payments.mismatched');
    Route::get('/payments/import', [PaymentController::class, 'import'])
        ->name('payments.import');
    Route::get('/payments/download-template', [PaymentController::class, 'downloadTemplate'])
        ->name('payments.download-template');

    Route::group(['prefix' => 'invoice-payments', 'as' => 'invoice-payments.'], function () {
        Route::get('/', [InvoicePaymentController::class, 'index'])->name('index');
        Route::get('/{user}/details', [InvoicePaymentController::class, 'show'])->name('show');
        Route::post('/update-field', [InvoicePaymentController::class, 'updateField'])->name('update-field');
        Route::patch('/{payment}/update-status', [InvoicePaymentController::class, 'updateStatus'])->name('update-status');
        Route::get('/export', [InvoicePaymentController::class, 'export'])->name('export');
        Route::get('/{user}/export', [InvoicePaymentController::class, 'exportDetailed'])->name('export-detailed');
        Route::patch('/{user}/payment-status', [InvoicePaymentController::class, 'updatePaymentStatus'])->name('update-payment-status');
    });

    Route::group(['prefix' => 'mismatched-payments', 'as' => 'mismatched-payments.'], function () {
        Route::get('/', [MismatchedPaymentController::class, 'index'])->name('index');
        Route::get('/export', [MismatchedPaymentController::class, 'export'])->name('export');
        Route::put('/{payment}', [MismatchedPaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [MismatchedPaymentController::class, 'destroy'])->name('destroy');
    });

    // User Documents
    Route::get('/user-documents/{idNumber}', [UserDocumentController::class, 'show'])
        ->name('banking.show');

    // Payment Routes with Parameters
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])
        ->name('payments.show');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])
        ->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])
        ->name('payments.destroy');
    Route::patch('/payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])
        ->name('payments.update-status');
    Route::post('/payments/update-field', [PaymentController::class, 'updateField'])
        ->name('payments.update-field');

    // Payment Cycle Routes
    Route::patch('/payment-cycles/{cycle}', [PaymentController::class, 'editCycle'])
        ->name('payment-cycles.edit');
    Route::delete('/payment-cycles/{cycle}', [PaymentController::class, 'destroyCycle'])
        ->name('payment-cycles.destroy');

    // Invoice Upload Routes
    Route::post('/payments/upload-combined-invoice', [PaymentController::class, 'uploadCombinedInvoice'])
        ->name('payments.upload-combined-invoice');
    Route::post('/payments/{payment}/upload-invoice', [PaymentController::class, 'uploadIndividualInvoice'])
        ->name('payments.upload-invoice');

    // Import Route
    Route::post('/payments/import', [PaymentController::class, 'processImport'])
        ->name('payments.process-import');

    // User Specific Routes
    Route::get('/my-payments', [PaymentController::class, 'userPayments'])
        ->name('payments.user');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    // Protected training routes
    Route::prefix('training')->name('training.')->group(function () {
        // View/list routes
        Route::get('/', [TrainingEventController::class, 'index'])
            ->name('index')
            ->middleware('permission:training.view');

        // Create routes
        Route::get('/create', [TrainingEventController::class, 'create'])
            ->name('create')
            ->middleware('permission:training.create');
        Route::post('/', [TrainingEventController::class, 'store'])
            ->name('store')
            ->middleware('permission:training.create');

        // Show/edit/update routes
        Route::get('/{id}', [TrainingEventController::class, 'show'])
            ->name('show')
            ->middleware('permission:training.view');
        Route::get('/{id}/edit', [TrainingEventController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:training.edit');
        Route::put('/{id}', [TrainingEventController::class, 'update'])
            ->name('update')
            ->middleware('permission:training.edit');
        Route::delete('/{id}', [TrainingEventController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:training.create');

        // Export routes
        Route::get('/{id}/export', [TrainingEventController::class, 'export'])
            ->name('export')
            ->middleware('permission:training.view');
        Route::get('/{id}/export/excel', [TrainingEventController::class, 'exportExcel'])
            ->name('export.excel')
            ->middleware('permission:training.view');

      
        Route::get('/{id}/history', [TrainingEventController::class, 'getAttendanceHistory'])
            ->name('history')
            ->middleware('permission:training.view');

      
        Route::post('/attendee/ban', [TrainingEventController::class, 'banAttendee'])
            ->name('ban-attendee')
            ->middleware('permission:training.ban');
        Route::delete('/attendee/{idNumber}/unban', [TrainingEventController::class, 'unbanAttendee'])
            ->name('unban-attendee')
            ->middleware('permission:training.ban');

      
        Route::post('/{id}/generate-restricted-link', [TrainingEventController::class, 'generateRestrictedLink'])
            ->name('generate-restricted-link')
            ->middleware('permission:training.view');
    });

    // Meeting Management Routes
    Route::prefix('meetings')->name('meetings.')->group(function () {
        // View/list routes
        Route::get('/', [MeetingController::class, 'index'])
            ->name('index')
            ->middleware('permission:meetings.view');

        // Create routes
        Route::get('/create', [MeetingController::class, 'create'])
            ->name('create')
            ->middleware('permission:meetings.create');
        Route::post('/', [MeetingController::class, 'store'])
            ->name('store')
            ->middleware('permission:meetings.create');

        // Show/edit/update routes
        Route::get('/{meeting}', [MeetingController::class, 'show'])
            ->name('show')
            ->middleware('permission:meetings.view');
        Route::get('/{meeting}/edit', [MeetingController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:meetings.edit');
        Route::put('/{meeting}', [MeetingController::class, 'update'])
            ->name('update')
            ->middleware('permission:meetings.edit');
        Route::delete('/{meeting}', [MeetingController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:meetings.delete');

        // Participant routes
        Route::post('/{meeting}/participants', [MeetingController::class, 'addParticipant'])
            ->name('participants.add')
            ->middleware('permission:meetings.edit');
        Route::delete('/{meeting}/participants/{participant}', [MeetingController::class, 'removeParticipant'])
            ->name('participants.remove')
            ->middleware('permission:meetings.edit');
        Route::post('/{meeting}/participants/{participant}/status', [MeetingController::class, 'updateParticipantStatus'])
            ->name('participants.update-status')
            ->middleware('permission:meetings.edit');

        // Document routes
        Route::post('/{meeting}/documents', [MeetingController::class, 'uploadDocument'])
            ->name('documents.upload')
            ->middleware('permission:meetings.edit');
        Route::delete('/{meeting}/documents/{document}', [MeetingController::class, 'deleteDocument'])
            ->name('documents.delete')
            ->middleware('permission:meetings.edit');

        // Action items routes
        Route::post('/{meeting}/actions', [MeetingController::class, 'addAction'])
            ->name('actions.add')
            ->middleware('permission:meetings.edit');
        Route::put('/{meeting}/actions/{action}', [MeetingController::class, 'updateAction'])
            ->name('actions.update')
            ->middleware('permission:meetings.edit');
        Route::delete('/{meeting}/actions/{action}', [MeetingController::class, 'deleteAction'])
            ->name('actions.delete')
            ->middleware('permission:meetings.edit');
    });
});


Route::prefix('t')->name('training.')->group(function () {
   
    Route::get('{slug}', [TrainingEventController::class, 'showForm'])
        ->name('form');
    Route::post('{slug}', [TrainingEventController::class, 'storeAttendance'])
        ->name('store-attendance');

   
    Route::post('{slug}/verify-location', [TrainingEventController::class, 'verifyLocation'])
        ->name('verify-location');

   
    Route::post('{slug}/verify-phone', [TrainingEventController::class, 'verifyPhone'])
        ->name('verify-phone');

    // Attendee search
    Route::get('search/{slug}', [TrainingEventController::class, 'searchAttendee'])
        ->name('search-attendee');
});

/*
|--------------------------------------------------------------------------
| Rateable Items Management Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::group([
    'middleware' => ['web', 'auth', 'permission:ratings.manage'],
    'prefix' => 'ratings',
    'as' => 'ratings.'
], function () {
    // Resource Routes
    Route::get('/', [RateableItemController::class, 'index'])->name('index');
    Route::get('/create', [RateableItemController::class, 'create'])->name('create');
    Route::post('/', [RateableItemController::class, 'store'])->name('store');
    Route::get('/{id}', [RateableItemController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RateableItemController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RateableItemController::class, 'update'])->name('update');
    Route::delete('/{id}', [RateableItemController::class, 'destroy'])->name('destroy');


    Route::get('/{id}/analytics', [RateableItemController::class, 'showAnalytics'])
        ->name('analytics');

    
    Route::prefix('{id}/export')->name('export.')->group(function () {
        Route::get('/', [RateableItemController::class, 'export'])->name('default');
        Route::get('/excel', [RateableItemController::class, 'exportExcel'])->name('excel');
    });


    Route::prefix('ratings')->group(function () {
        Route::post('/{id}/hide', [RateableItemController::class, 'hideRating'])
            ->name('hide-rating');
        Route::post('/{id}/show', [RateableItemController::class, 'showRating'])
            ->name('show-rating');
    });

    // Utility Routes
    Route::get('/{id}/share-link', [RateableItemController::class, 'generateShareableLink'])
        ->name('share-link');
});

/*
|--------------------------------------------------------------------------
| Public Rating Routes
|--------------------------------------------------------------------------
*/
Route::prefix('r')->name('ratings.')->group(function () {
    // Rating Form & Submission
    Route::get('{slug}', [RateableItemController::class, 'showRatingForm'])
        ->name('form')
        ->middleware('web');

    Route::post('{slug}', [RateableItemController::class, 'submitRating'])
        ->name('submit')
        ->middleware(['web', 'throttle:60,1']);

    // AJAX Validation (with rate limiting)
    Route::middleware(['web', 'throttle:60,1'])->group(function () {
        Route::post('{slug}/validate-email', [RateableItemController::class, 'validateEmail'])
            ->name('validate-email');

        Route::post('{slug}/check-duplicate', [RateableItemController::class, 'checkDuplicate'])
            ->name('check-duplicate');
    });
});

Route::group([
    'middleware' => ['web', 'auth', 'permission:field-reports.manage'],
    'prefix' => 'field-reports',
    'as' => 'field-reports.'
], function () {
    // Basic CRUD routes
    Route::get('/', [FieldReportController::class, 'index'])->name('index');
    Route::get('/create', [FieldReportController::class, 'create'])->name('create');
    Route::post('/', [FieldReportController::class, 'store'])->name('store');
    Route::get('/{report}', [FieldReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [FieldReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [FieldReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [FieldReportController::class, 'destroy'])->name('destroy');

    // Approval workflow
    Route::post('/{report}/approve', [FieldReportController::class, 'approve'])
        ->name('approve')
        ->middleware('permission:field-reports.approve');

    // Attachment handling
    Route::delete('/{report}/attachments/{attachment}', [FieldReportController::class, 'removeAttachment'])
        ->name('remove-attachment');
});

Route::group([
    'middleware' => ['web', 'auth', 'permission:general-reports.manage'],
    'prefix' => 'general-reports',
    'as' => 'general-reports.'
], function () {
    // Basic CRUD routes
    Route::get('/', [GeneralReportController::class, 'index'])->name('index');
    Route::get('/dashboard', [GeneralReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [GeneralReportController::class, 'create'])->name('create');
    Route::post('/', [GeneralReportController::class, 'store'])->name('store');
    Route::get('/{report}', [GeneralReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [GeneralReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [GeneralReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [GeneralReportController::class, 'destroy'])->name('destroy');

    // Approval workflow
    Route::post('/{report}/approve', [GeneralReportController::class, 'approve'])
        ->name('approve')
        ->middleware('permission:general-reports.approve');

    // Attachment handling
    Route::delete('/{report}/attachments/{attachment}', [GeneralReportController::class, 'removeAttachment'])
        ->name('remove-attachment');
});

Route::group([
    'middleware' => ['web', 'auth', 'permission:back-to-office-reports.manage'],
    'prefix' => 'back-to-office-reports',
    'as' => 'back-to-office-reports.'
], function () {
    // Basic CRUD routes
    Route::get('/', [BackToOfficeReportController::class, 'index'])->name('index');
    Route::get('/dashboard', [BackToOfficeReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [BackToOfficeReportController::class, 'create'])->name('create');
    Route::post('/', [BackToOfficeReportController::class, 'store'])->name('store');
    Route::get('/{report}', [BackToOfficeReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [BackToOfficeReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [BackToOfficeReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [BackToOfficeReportController::class, 'destroy'])->name('destroy');

    // Approval workflow
    Route::post('/{report}/approve', [BackToOfficeReportController::class, 'approve'])
        ->name('approve')
        ->middleware('permission:back-to-office-reports.approve');

    // Attachment handling
    Route::delete('/{report}/attachments/{attachment}', [BackToOfficeReportController::class, 'removeAttachment'])
        ->name('remove-attachment');
    // PDF export
    Route::get('/{report}/export-pdf', [BackToOfficeReportController::class, 'exportPdf'])
        ->name('exportPdf');
});

// Static Field Activities page
Route::get('/field-activities-static', function () {
    return response()->file(public_path('field-activities-static.html'));
});




// Field Activities routes (inside auth/verified/check.onboarding/nda middleware)
Route::group(['middleware' => ['auth', 'verified', 'check.onboarding', 'nda']], function () {
    Route::get('/field-activities', function() {
        $user = auth()->user();
        return view('field-activities.fam', compact('user'));
    })->name('field-activities.index');
    Route::get('/field-activities/create', [\App\Http\Controllers\Web\FieldActivityController::class, 'create'])->name('field-activities.create');

    Route::get('/field-activities/fam', function() {
        $user = auth()->user();
        // TODO: Replace this demo array with real logic from controller/model
        $pendingActions = [
            [
                'title' => 'Supervisor Review Needed',
                'subtitle' => 'Community Sensitization · 2 days ago',
                'button' => 'Review Activity',
                'button_class' => 'btn-brand',
                'button_action' => "showView('detail');switchTab('overview')",
                'border' => '#fde68a',
                'background' => '#fffbeb',
            ],
            [
                'title' => 'GPS Pending Verification',
                'subtitle' => 'Mombasa CBD → Nyali route',
                'button' => 'Update Transport',
                'button_class' => 'btn-outline-secondary',
                'button_action' => "showView('detail');switchTab('field')",
                'border' => 'var(--border)',
                'background' => 'white',
            ],
        ];
        return view('field-activities.fam', compact('user', 'pendingActions'));
    })->name('field-activities.fam');
});



Route::prefix('bot')
    ->name('bot.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/', [BotAdminController::class, 'index'])->name('index');
        Route::get('/compose', [BotAdminController::class, 'compose'])->name('compose');
        Route::post('/send', [BotAdminController::class, 'send'])->name('send');

        Route::get('/batch/{batchId}', [BotAdminController::class, 'batchDetails'])
            ->name('batch.details');

        Route::post('/recipients', [BotAdminController::class, 'getRecipients'])->name('recipients');

        Route::get('/templates', [BotAdminController::class, 'templates'])->name('templates');
        Route::post('/templates', [BotAdminController::class, 'storeTemplate'])->name('templates.store');

        Route::get('/issues', [BotAdminController::class, 'issues'])->name('issues');
        Route::put('/issues/{id}', [BotAdminController::class, 'updateIssueStatus'])->name('issues.update');

        Route::get('/ratings', [BotAdminController::class, 'ratings'])->name('ratings');
        Route::get('/issues', [BotAdminController::class, 'issues'])->name('issues');
        Route::put('/issues/{id}', [BotAdminController::class, 'updateIssueStatus'])->name('issues.update');
        Route::get('/ratings', [BotAdminController::class, 'ratings'])->name('ratings');
        Route::get('/messages/recent', [BotAdminController::class, 'recentMessages'])->name('messages.recent');
        Route::get('/queue/status', [BotAdminController::class, 'queueStatus'])->name('queue.status');
        // Inside the bot prefix group
        Route::get('/batch/{batchId}/rating-stats', [BotAdminController::class, 'getBatchRatingStats'])
            ->name('batch.rating-stats');

    });

    // Download routes for HR Policy and NDA
Route::get('/download/hr-policy', function () {
    $path = public_path('documents/hr_policy.pdf');
    if (!file_exists($path)) abort(404);
    return response()->download($path, 'CPHRM_HR_Policy.pdf');
})->name('download.hr_policy');


Route::get('/download/nda', function () {
    $path = public_path('documents/nda.pdf');
    if (!file_exists($path)) abort(404);
    return response()->download($path, 'CPHRM_NDA.pdf');
})->name('download.nda');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('regions', RegionController::class)
        ->middleware('permission:regions.manage');
});


// NDA routes
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/nda', [\Vanguard\Http\Controllers\Web\NdaController::class, 'show'])->name('nda.show');
    Route::post('/nda/accept', [\Vanguard\Http\Controllers\Web\NdaController::class, 'accept'])->name('nda.accept');
    Route::get('/nda/download', [\Vanguard\Http\Controllers\Web\NdaController::class, 'download'])->name('nda.download');
});

// Data Collection routes (fixed)
Route::middleware(['auth'])->group(function () {
    Route::get('data-collection', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'index'])->name('data-collection.index');

    Route::middleware('can:manage,App\\Models\\DataCollection')->prefix('admin/data-collection')->name('admin.data-collection.')->group(function () {
        Route::get('/', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'adminIndex'])->name('index');
        Route::get('/create', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'create'])->name('create');
        Route::post('/', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'store'])->name('store');
        Route::get('/{data_collection}/edit', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'edit'])->name('edit');
        Route::put('/{data_collection}', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'update'])->name('update');
        Route::delete('/{data_collection}', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'destroy'])->name('destroy');
    });
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/get-wards', [UsersController::class, 'getWards'])->name('get-wards');
    Route::get('/get-subcounties', [UsersController::class, 'getSubcounties'])->name('get-subcounties');
});

Route::get('/support/attachment/download/{filename}', [
    \Vanguard\Http\Controllers\Web\Support\SupportIssueController::class,
    'downloadAttachment'
])->name('support.attachment.download')->middleware('auth');

// Data Collection user view route (embedded form)
Route::get('data-collection/{slug}/view', [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'view'])->name('data-collection.view');

Route::post('admin/data-collection/{data_collection}/toggle-status', 
    [\Vanguard\Http\Controllers\Web\DataCollectionController::class, 'toggleStatus'])
    ->name('admin.data-collection.toggle-status');

Route::get('/api/users/search', [UsersController::class, 'search'])->name('users.search');
Route::get('/contracts/{contract}/search-users', [ContractController::class, 'searchUsers'])->name('contracts.search-users');
Route::get('/contracts/search-users', [ContractController::class, 'searchUsers'])->name('contracts.search-users');