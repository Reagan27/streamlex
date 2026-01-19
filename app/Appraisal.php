<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'appraiser_id', 'appraisal_date', 'period',
        'motivation_score', 'resourcefulness_score', 'leadership_score',
        'discipline_score', 'teamwork_score',
        'comments', 'status'
    ];

    protected $casts = [
        'appraisal_date' => 'date',
        'status' => 'boolean',
    ];

    protected $dates = ['appraisal_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appraiser()
    {
        return $this->belongsTo(User::class, 'appraiser_id');
    }

    public function markAsCompleted() {
        $this->status = true;
        $this->save();
    }
}