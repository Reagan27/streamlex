<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityDocument extends Model
{
    protected $fillable = [
        'field_activity_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    public function fieldActivity()
    {
        return $this->belongsTo(FieldActivity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
