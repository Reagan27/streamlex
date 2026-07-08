<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityActualExpense extends Model
{
    protected $fillable = [
        'activity_id', 'description', 'date', 'amount'
    ];

    public function activity()
    {
        return $this->belongsTo(FieldActivity::class, 'activity_id');
    }
}
