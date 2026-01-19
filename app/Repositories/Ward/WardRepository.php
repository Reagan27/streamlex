<?php

namespace Vanguard\Repositories\Ward;

use Illuminate\Support\Collection;

interface WardRepository
{
    public function lists(string $subcountyId, string $column = 'name', string $key = 'id'): Collection;
}
