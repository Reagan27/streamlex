<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityExpense extends Model
{
    protected $fillable = [
        'field_activity_id',
        'description',
        'category',
        'amount',
        'actual_amount',
        'expense_date',
    ];

    public function fieldActivity()
    {
        return $this->belongsTo(FieldActivity::class);
    }
}
