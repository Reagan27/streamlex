<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'budget', 
        'start_date', 
        'end_date', 
        'is_active',
        'function_key',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users associated with this project
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_user', 'project_id', 'user_id')
                    ->using(ProjectUser::class)
                    ->withPivot('is_active_project')
                    ->withTimestamps();
    }

    /**
     * Get the admin contracts associated with this project
     */
    public function adminContracts()
    {
        return $this->hasMany(AdminContract::class, 'project_id');
    }

    /**
     * Scope to get only active projects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get projects within date range
     */
    public function scopeCurrentlyActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Check if project is currently within its active date range
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get the number of users assigned to this project
     */
    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }
}