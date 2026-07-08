<?php

namespace Vanguard;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Mail;
use Vanguard\Events\User\RequestedPasswordResetEmail;
use Vanguard\Presenters\Traits\Presentable;
use Vanguard\Projects;
use Vanguard\Presenters\UserPresenter;
use Vanguard\Support\Authorization\AuthorizationUserTrait;
use Vanguard\Support\CanImpersonateUsers;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\Traits\AutoOnboardingTrait;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $username
 * @property string $phone
 * @property boolean $first_authentication
 * @property boolean $initial_password
 * @property string|null $avatar
 * @property int|null $county_id
 * @property int|null $subcounty_id
 * @property int|null $ward_id
 * @property Carbon $last_login
 * @property Carbon $birthday
 * @property UserStatus $status
 * @property string|null $confirmation_token
 * @property string|null $remember_token
 * @property int $role_id
 * @property Carbon|null $email_verified_at
 * @property string $two_factor_country_code
 * @property string $two_factor_phone
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use AuthorizationUserTrait,
        CanImpersonateUsers,
        CanResetPassword,
        HasApiTokens,
        HasFactory,
        Notifiable,
        Presentable,
        AutoOnboardingTrait,
        TwoFactorAuthenticatable;

    protected string $presenter = UserPresenter::class;
    protected $table = 'users';

    protected $fillable = [
        'email', 'password', 'username', 'first_name', 'last_name', 'phone', 'first_authentication', 'initial_password', 'avatar',
        'country_id','county_id', 'subcounty_id', 'ward_id', 'birthday', 'last_login', 'confirmation_token', 'status',
        'remember_token', 'role_id', 'email_verified_at', 'supervisor_id','completed','policy_agreed',
        'address', 'banking_submitted', 'documents_submitted', 'contract_signed',
        'onboarding_completed_at', 'role_status','onboarding_status', 'approved_for_payment',
        'contract_type',
        // Employee Info fields
        'nok_full_name', 'nok_relationship', 'nok_mobile', 'nok_alt_phone', 'nok_email', 'nok_address',
        'marital_status', 'spouse_name', 'spouse_contact', 'dependents',
        'employee_number', 'department', 'job_title', 'employment_type', 'employment_date',
        'work_station', 'supervisor_name', 'supervisor_title',
        'blood_group', 'medical_conditions', 'allergies', 'medical_facility',
        'disability', 'disability_details', 'workplace_adjustments',
        // Statutory & Compliance Declarations
        'info_accurate', 'info_authorize', 'info_falsified'
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'birthday' => 'date',
        'updated_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'role_status' => 'integer',
        'completed' => 'boolean',
        'onboarding_status' => 'boolean',
        'approved_for_payment' => 'boolean',
        'status' => UserStatus::class,
        'nda_signed_at' => 'datetime',
    ];

    protected $hidden = ['password', 'remember_token'];

    // ------------------------
    // Accessors & Mutators
    // ------------------------
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setBirthdayAttribute($value): void
    {
        $this->attributes['birthday'] = trim($value) ?: null;
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBankBranchAttribute()
    {
        if ($this->manualBankDetails && $this->manualBankDetails->use_manual_details) {
            return [
                'name' => $this->manualBankDetails->manual_branch_name,
                'code' => $this->manualBankDetails->manual_branch_code
            ];
        }

        return $this->bankDetails ? BankBranch::where('branch_code', $this->bankDetails->bank_branch)->first() : null;
    }


    public function educationDocuments()
{
    return $this->hasMany(\App\Models\EducationDocument::class);
}
public function otherDocuments()
{
    return $this->hasMany(\App\Models\OtherDocument::class);
}

    // ------------------------
    // Authentication & Notifications
    // ------------------------
    public function gravatar(): string
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));
        return sprintf('https://www.gravatar.com/avatar/%s?size=150', $hash);
    }

    public function sendPasswordResetNotification($token): void
    {
        Mail::to($this)->send(new \Vanguard\Mail\ResetPassword($token));
        event(new RequestedPasswordResetEmail($this));
    }

    public function twoFactorEnabled(): bool
    {
        return !!$this->two_factor_confirmed_at && !!$this->two_factor_secret;
    }

    public function needsTwoFactorVerification(): bool
    {
        return !$this->two_factor_confirmed_at && !!$this->two_factor_secret;
    }

    // ------------------------
    // Status Checks
    // ------------------------
    public function isUnconfirmed(): bool { return $this->status == UserStatus::UNCONFIRMED; }
    public function isActive(): bool { return $this->status == UserStatus::ACTIVE; }
    public function isBanned(): bool { return $this->status == UserStatus::BANNED; }
    public function isInitialPasswordExpired(): bool
    {
        return $this->initial_password && $this->created_at->diffInDays(now()) > 7;
    }

    public function isOnboardingComplete(): bool { return $this->onboarding_status; }

    // ------------------------
    // Role & Hierarchy
    // ------------------------
    public function role(): BelongsTo { return $this->belongsTo(Role::class); }
    public function roles(): BelongsToMany { return $this->belongsToMany(Role::class); }

    public function hasRole($roles): bool
    {
        if (is_array($roles)) return in_array($this->role->name, $roles);
        return $this->role->name === $roles;
    }

    public function assignRole(Role $role) { $this->setRole($role); }
    public function setRole(Role $role)
    {
        $this->role()->associate($role);
        $this->role_status = true;
        $this->save();
    }
    public function removeRole()
    {
        $this->role()->dissociate();
        $this->role_status = false;
        $this->save();
    }

    public function hasHigherHierarchyThan(User $otherUser): bool
    {
        $hierarchy = [
            'Admin' => 1,
            'Manager' => 2,
            'Regional_Coordinator' => 3,
            'County_Coordinator' => 4,
            'Supervisor' => 5,
            'Field_Officer' => 6
        ];
        return $hierarchy[$this->role->name] < $hierarchy[$otherUser->role->name];
    }

    public function canSupervise(User $user): bool
    {
        if ($this->isAdmin() || $this->isManager()) return true;

        if ($this->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $this->counties()->pluck('counties.id')->toArray();
            return $user->role->name === 'County_Coordinator' && in_array($user->county_id, $assignedCountyIds);
        }

        if ($this->role->name === 'County_Coordinator') {
            return $user->role->name === 'Supervisor' && $user->county_id === $this->county_id;
        }

        if ($this->role->name === 'Supervisor') {
            return $user->role->name === 'Field_Officer' && $user->subcounty_id === $this->subcounty_id;
        }

        return false;
    }

    public function canAssign(User $superior, User $subordinate): bool
    {
        if ($this->isAdmin() || $this->isManager()) return true;

        if ($this->role->name === 'Regional_Coordinator') {
            return $superior->role->name === 'County_Coordinator' &&
                   $subordinate->role->name === 'Supervisor' &&
                   $this->counties->contains($superior->county_id) &&
                   $this->counties->contains($subordinate->county_id);
        }

        if ($this->role->name === 'County_Coordinator') {
            return $superior->role->name === 'Supervisor' &&
                   $subordinate->role->name === 'Field_Officer' &&
                   $this->county_id === $superior->county_id &&
                   $this->county_id === $subordinate->county_id;
        }

        return false;
    }

    public function isAdmin(): bool { return $this->hasRole('Admin'); }
    public function isManager(): bool { return $this->hasRole('Manager'); }
    public function isRegionalCoordinator(): bool { return $this->role && $this->role->name === 'Regional_Coordinator'; }

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->isDirty('role_id')) {
                $user->role_status = !is_null($user->role_id);
                $user->saveQuietly();
            }
        });
    }

    // ------------------------
    // Relationships
    // ------------------------
    public function supervisor(): BelongsTo { return $this->belongsTo(User::class, 'supervisor_id'); }
    public function fieldOfficers(): HasMany { return $this->hasMany(User::class, 'supervisor_id'); }
    public function subordinates(): HasMany { return $this->hasMany(User::class, 'supervisor_id'); }
    public function regionalCoordinatorCounties(): HasMany { return $this->hasMany(RegionalCoordinatorCounty::class); }
    public function counties(): BelongsToMany
    {
        return $this->belongsToMany(County::class, 'regional_coordinator_counties', 'user_id', 'county_id');
    }
    public function county(): BelongsTo { return $this->belongsTo(County::class); }
    public function subcounty(): BelongsTo { return $this->belongsTo(Subcounty::class); }
    public function ward(): BelongsTo { return $this->belongsTo(Ward::class); }
    public function assets(): HasMany { return $this->hasMany(Asset::class, 'assigned_to'); }
    public function appraisals(): HasMany { return $this->hasMany(Appraisal::class); }
    public function bankDetails(): HasOne { return $this->hasOne(UserBankDetail::class); }
    public function manualBankDetails(): HasOne { return $this->hasOne(UserManualBankDetails::class); }
    public function documents(): HasMany { return $this->hasMany(UserDocument::class); }
    public function contractSignature(): HasOne { return $this->hasOne(UserContractSignature::class); }
    public function contractSignatures(): HasMany { return $this->hasMany(UserContractSignature::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function distributedAssets(): HasMany { return $this->hasMany(AssetDistribution::class, 'distributed_to'); }
    public function assetAssignments(): HasMany { return $this->hasMany(AssetAssignment::class, 'assigned_to'); }

    // ------------------------
    // Projects Integration
    // ------------------------
    
    /**
     * The projects that belong to the user.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Projects::class, 'projects_user', 'user_id', 'project_id')
                    ->using(ProjectUser::class)
                    ->withPivot('is_active_project')
                    ->withTimestamps();
    }

    /**
     * Get the user's currently active project.
     */
    public function activeProject()
    {
        return $this->projects()
                    ->wherePivot('is_active_project', true)
                    ->first();
    }

    /**
     * Check if a specific project is the user's active project.
     */
    public function hasActiveProject($projectId): bool
    {
        return $this->projects()
                    ->where('projects.id', $projectId)
                    ->wherePivot('is_active_project', true)
                    ->exists();
    }

    /**
     * Set a project as the active project for this user.
     */
    public function setActiveProject(Projects $project)
    {
        // First, deactivate all projects for this user
        $this->projects()->updateExistingPivot(
            $this->projects()->pluck('projects.id'), 
            ['is_active_project' => false]
        );
        
        // Then set the new active project
        $this->projects()->syncWithoutDetaching([
            $project->id => ['is_active_project' => true]
        ]);
        
        // Update session
        session(['active_project_id' => $project->id]);
        session(['active_project_name' => $project->name]);
    }

    /**
     * Clear the active project for this user.
     */
    public function clearActiveProject()
    {
        $this->projects()->updateExistingPivot(
            $this->projects()->pluck('projects.id'), 
            ['is_active_project' => false]
        );
        
        // Clear session
        session()->forget(['active_project_id', 'active_project_name']);
    }

    /**
     * Get the active project ID from session or database.
     */
    public function getActiveProjectId()
    {
        // Try session first
        if (session()->has('active_project_id')) {
            return session('active_project_id');
        }

        // Fall back to database
        $activeProject = $this->activeProject();
        if ($activeProject) {
            session(['active_project_id' => $activeProject->id]);
            session(['active_project_name' => $activeProject->name]);
            return $activeProject->id;
        }

        return null;
    }
}