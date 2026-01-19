<?php

namespace Vanguard;

use Vanguard\Message;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageBatch extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $table = 'messages_batch';

    protected $fillable = [
        'batchId',
        'date_time',
        'count',
        'sent',
        'group_id',
        'company_id',     
        'created_at',
        'updated_at',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'batchId'  => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'batchId' => 'required',
    ];

    /**
     * Get all messages associated with the batch.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'batch_id', 'batchID');
    }

    /**
     * Get the batch that this message belongs to.
     *
     * @return BelongsTo
     */
    public function batch()
    {
        return $this->belongsTo(MessageBatch::class, 'batch_id', 'batchId');
    }

    /**
     * Accessor for formatted created_at.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * Accessor for formatted updated_at.
     *
     * @return string
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
