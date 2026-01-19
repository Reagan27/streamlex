<?php

namespace Vanguard\Repositories\Ward;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Vanguard\Ward;

class EloquentWard implements WardRepository
{
    public function lists(string $subcountyId, string $column = 'name', string $key = 'id'): Collection
    {
        return Ward::where('subcounty_id', $subcountyId)->orderBy($column)->pluck($column, $key);
    }

    public function listsForSubcounty(string $subcountyId): Collection
    {
        return $this->lists($subcountyId);
    }

    public function all(): EloquentCollection
    {
        return Ward::all();
    }
}
