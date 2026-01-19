<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class EmailBatch extends Model
{
    protected $table = 'email_batches';

    protected $fillable = [
        'batchId',
        'email_count',
        'status',
        'created_at',
        'updated_at',
        'category',
        'count',
        'sent',
        'user_id',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class, 'batch_id', 'batchId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
