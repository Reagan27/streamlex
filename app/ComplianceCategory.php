<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceCategory extends Model
{
    use SoftDeletes;

    protected $table = 'compliance_categories';

    protected $fillable = ['name', 'slug', 'description'];

    public function documents()
    {
        return $this->hasMany(ComplianceDocument::class, 'category_id');
    }
}
