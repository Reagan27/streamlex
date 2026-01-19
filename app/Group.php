<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type'];

    const TYPE_CONTACT = 'contact';
    const TYPE_EMAIL = 'email';

    /**
     * A group can have many contacts.
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_group', 'group_id', 'contact_id')
                    ->withTimestamps();
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'email_group', 'group_id', 'email_id')
                    ->withTimestamps();
    }
}
