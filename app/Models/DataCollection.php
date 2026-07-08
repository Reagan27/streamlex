<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DataCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'iframe_code',
        'sheet_url',
        'project_id',
        'slug',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (empty($model->slug) && !empty($model->title)) {
                $slug = Str::slug($model->title);
                $originalSlug = $slug;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = $originalSlug . '-' . $i++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}