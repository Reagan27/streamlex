<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
    'name', 
    'phone',
    'email',
    'group_id',
    ];

    /**
     * A contact can belong to many groups.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'contact_group', 'contact_id', 'group_id')
                    ->withTimestamps();
    }
}
