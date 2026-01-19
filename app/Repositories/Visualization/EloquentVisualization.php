<?php

namespace Vanguard\Repositories\Visualization;

use Vanguard\Visualization; // Assuming you have a Visualization model
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentVisualization implements VisualizationRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection
    {
        if ($perPage) {
            return Visualization::paginate($perPage);
        }

        return Visualization::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): mixed
    {
        return Visualization::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): mixed
    {
        return Visualization::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): mixed
    {
        $visualization = $this->find($id);
        if ($visualization) {
            $visualization->update($data);
        }
        return $visualization;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $visualization = $this->find($id);
        return $visualization ? $visualization->delete() : false;
    }
}
