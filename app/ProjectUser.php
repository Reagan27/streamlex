<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'is_active_project'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active_project' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project that owns the pivot.
     */
    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    /**
     * Get the user that owns the pivot.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}