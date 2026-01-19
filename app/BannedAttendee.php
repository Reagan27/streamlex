<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedAttendee extends Model
{
    protected $fillable = ['id_number', 'reason', 'banned_at', 'banned_by'];
    
    protected $casts = [
        'banned_at' => 'datetime'
    ];
    
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
