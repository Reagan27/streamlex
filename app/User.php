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

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'username', 'first_name', 'last_name', 'phone', 'avatar',
        'country_id','county_id', 'subcounty_id', 'ward_id', 'birthday', 'last_login', 'confirmation_token', 'status',
        'remember_token', 'role_id', 'email_verified_at', 'supervisor_id','completed','policy_agreed',
        'address', 'country_id', 'banking_submitted', 'documents_submitted', 'contract_signed',
        'onboarding_completed_at', 'role_status','birthday','onboarding_status', 'approved_for_payment'
       
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'birthday' => 'date',
        'updated_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'birthday' => 'date',
        'role_status' => 'integer',
        'completed' => 'boolean',
        'onboarding_status' => 'boolean',
        'approved_for_payment' => 'boolean',
        'status' => UserStatus::class,
    ];

  

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Always encrypt password when it is updated.
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setBirthdayAttribute($value): void
    {
        $this->attributes['birthday'] = trim($value) ?: null;
    }

    public function gravatar(): string
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));

        return sprintf('https://www.gravatar.com/avatar/%s?size=150', $hash);
    }

    public function isUnconfirmed(): bool
    {
        return $this->status == UserStatus::UNCONFIRMED;
    }

    public function isActive(): bool
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isBanned(): bool
    {
        return $this->status == UserStatus::BANNED;
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

    public function assets()
    {
        return $this->hasMany(Asset::class, 'assigned_to');
    }

    // Relationships
    public function counties()
    {
        return $this->belongsToMany(County::class, 'regional_coordinator_counties', 'user_id', 'county_id');
    }

    
    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function subcounty()
    {
        return $this->belongsTo(Subcounty::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
  


    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role->name, $roles);
        }
        
        return $this->role->name === $roles;
    }

    public function assignCountiesToUser(int $userId, array $countyIds): bool
    {
        $user = $this->find($userId);
        return $user->counties()->sync($countyIds);
    }
    
    public function bankDetails()
    {
        return $this->hasOne(UserBankDetail::class);
    }
    
    public function documents()
    {
        return $this->hasOne(UserDocument::class);
    }
    
    public function contractSignature()
    {
        return $this->hasOne(UserContractSignature::class);
    }

    // public function isOnboardingCompleted(): bool
    // {
    //     return $this->banking_submitted && 
    //            $this->documents_submitted && 
    //            $this->contract_signed && 
    //            $this->onboarding_completed_at !== null;
    // }

    public function isOnboardingComplete()
    {
        return $this->onboarding_status;
    }
    public function getNameAttribute()
{
    return $this->first_name . ' ' . $this->last_name;
}

public function contractSignatures()
{
    return $this->hasMany(UserContractSignature::class);
}


public function assignRole(Role $role)
{
    $this->role()->associate($role);
    $this->role_status = true;
    $this->save();
}

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
public function supervisor(): BelongsTo
{
    return $this->belongsTo(User::class, 'supervisor_id');
}

public function fieldOfficers(): HasMany
{
    return $this->hasMany(User::class, 'supervisor_id');
}

public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function regionalCoordinatorCounties()
    {
        return $this->hasMany(RegionalCoordinatorCounty::class);
    }

public function canSupervise(User $user): bool
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }
        

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

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isManager()
{
    return $this->hasRole('Manager');
}
public function isRegionalCoordinator()
{
    return $this->role && $this->role->name === 'Regional_Coordinator';
}
protected static function booted()
{
    static::saved(function ($user) {
        if ($user->isDirty('role_id')) {
            $user->role_status = !is_null($user->role_id);
            $user->saveQuietly();
        }
    });
}

public function canAssign(User $superior, User $subordinate): bool
    {
        // Implement your logic here. For example:
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }
        

        if ($this->role->name === 'Regional_Coordinator') {
            // Check if the superior is a County Coordinator and the subordinate is a Supervisor
            return $superior->role->name === 'County_Coordinator' 
                && $subordinate->role->name === 'Supervisor'
                && $this->counties->contains($superior->county_id)
                && $this->counties->contains($subordinate->county_id);
        }

        if ($this->role->name === 'County_Coordinator') {
            // Check if the superior is a Supervisor and the subordinate is a Field Officer
            return $superior->role->name === 'Supervisor' 
                && $subordinate->role->name === 'Field_Officer'
                && $this->county_id === $superior->county_id
                && $this->county_id === $subordinate->county_id;
        }

        return false; // By default, users can't assign
    }

    public function hasHigherHierarchyThan(User $otherUser)
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

public function getRouteKeyName()
{
    return 'id';
}

public function payments(): HasMany
{
    return $this->hasMany(Payment::class);
}

public function distributedAssets()
{
    return $this->hasMany(AssetDistribution::class, 'distributed_to');
}

public function assetAssignments()
{
    return $this->hasMany(AssetAssignment::class, 'assigned_to');
}

public function manualBankDetails(): HasOne
{
    return $this->hasOne(UserManualBankDetails::class);
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
}
