<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class IssuesCategory extends Model
{
    protected $fillable = ['name'];

    public function supportIssues()
    {
        return $this->hasMany(SupportIssue::class, 'category_id');
    }
}

