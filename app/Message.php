<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recipient',
        'phone',
        'message',
        'status',
        'message_id',
        'message_cost',
        'created_at',
        'updated_at',
    ];

    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_FAILED = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_UNDELIVERED = 4;

    /**
     * Get the status description.
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_SENT:
                return 'Sent';
            case self::STATUS_FAILED:
                return 'Failed';
            case self::STATUS_DELIVERED:
                return 'Delivered';
            case self::STATUS_UNDELIVERED:
                return 'Undelivered';
            default:
                return 'Unknown';
        }
    }

    /**
     * Set the message content.
     *
     * @param string $value
     */
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = strip_tags($value);
    }

    /**
     * Get the formatted created_at timestamp.
     *
     * @return string|null
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * Get the formatted message cost.
     *
     * @return string|null
     */
    public function getFormattedMessageCostAttribute()
    {
        return $this->message_cost ? number_format($this->message_cost, 2) . ' USD' : null;
    }

    public function scopeNonTemplates($query)
{
    return $query->where('category', '!=', 'template')->orWhereNull('category');
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
