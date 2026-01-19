<?php

namespace Vanguard\Repositories\Subcounty;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Vanguard\Subcounty;

class EloquentSubcounty implements SubcountyRepository
{
    public function lists(string $countyId, string $column = 'name', string $key = 'id'): Collection
    {
        return Subcounty::where('county_id', $countyId)->orderBy($column)->pluck($column, $key);
    }

    public function listsForCounty(string $countyId, string $column = 'name', string $key = 'id'): Collection
    {
        return Subcounty::where('county_id', $countyId)
            ->orderBy($column)
            ->pluck($column, $key);
    }

    public function all(): EloquentCollection
    {
        return Subcounty::all();
    }
}
