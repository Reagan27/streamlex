<?php

namespace Vanguard\Repositories\County;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Vanguard\County;

class EloquentCounty implements CountyRepository
{
    public function lists(string $column = 'name', string $key = 'id'): Collection
    {
        return County::orderBy($column)->pluck($column, $key);
    }

    public function all(): EloquentCollection
    {
        return County::all();
    }
}
