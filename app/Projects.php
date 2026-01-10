<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'budget', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_user', 'project_id', 'user_id') // Explicit foreign keys
                    ->using(ProjectUser::class)
                    ->withPivot('is_active_project')
                    ->withTimestamps();
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
