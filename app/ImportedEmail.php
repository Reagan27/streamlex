<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ImportedEmail extends Model
{
    protected $table = 'imported_emails';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'group_id',
    ];

    /**
     * Get the group that owns the imported email.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Scope a query to only include emails from a specific group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromGroup($query, int $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Validate the email format.
     *
     * @param string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Retrieve the formatted phone number.
     *
     * @return string
     */
    public function getFormattedPhoneAttribute(): string
    {
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $this->phone_number);
    }
}
