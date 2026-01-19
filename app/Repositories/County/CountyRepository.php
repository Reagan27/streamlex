<?php

namespace Vanguard\Repositories\County;

use Illuminate\Support\Collection;

interface CountyRepository
{
    public function lists(string $column = 'name', string $key = 'id'): Collection;
}
