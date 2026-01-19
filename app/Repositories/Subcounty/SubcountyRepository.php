<?php

namespace Vanguard\Repositories\Subcounty;

use Illuminate\Support\Collection;

interface SubcountyRepository
{
    public function lists(string $countyId, string $column = 'name', string $key = 'id'): Collection;

    public function listsForCounty(string $countyId, string $column = 'name', string $key = 'id'): Collection;
}
