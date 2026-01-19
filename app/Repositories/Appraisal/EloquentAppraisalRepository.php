<?php

namespace Vanguard\Repositories\Appraisal;

use Illuminate\Database\Eloquent\Collection;
use Vanguard\Appraisal;

class EloquentAppraisalRepository implements AppraisalRepository
{
    public function all(): Collection
    {
        return Appraisal::all();
    }

    public function find(int $id): ?Appraisal
    {
        return Appraisal::find($id);
    }

    public function create(array $data): Appraisal
    {
        return Appraisal::create($data);
    }

    public function update(int $id, array $data): Appraisal
    {
        $appraisal = $this->find($id);
        $appraisal->update($data);
        return $appraisal;
    }

    public function delete(int $id): bool
    {
        return Appraisal::destroy($id) > 0;
    }

    public function getAppraisalsForUser(int $userId): Collection
    {
        return Appraisal::where('user_id', $userId)->get();
    }

    public function getAppraisalsByCounties(array $countyIds): Collection
    {
        return Appraisal::whereHas('user', function ($query) use ($countyIds) {
            $query->whereIn('county_id', $countyIds);
        })->get();
    }

    public function count(): int
    {
        return Appraisal::count();
    }

    public function query()
    {
        return Appraisal::query();
    }
}