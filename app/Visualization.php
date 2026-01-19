<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Visualization extends Model
{
    protected $table = 'visualizations';

    protected $fillable = [
        'name',
        'description',
        'url',
        'created_at',
        'updated_at',
    ];
}
