<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class RecommendationCertificate extends Model
{
    protected $table = 'recommendation_certificates';
    
    protected $fillable = [
        'name',
        'type',
        'threshold',
        'threshold_status',
        'content',
    ];

    protected $casts = [
        'threshold' => 'float',
        'threshold_status' => 'boolean',
    ];

}