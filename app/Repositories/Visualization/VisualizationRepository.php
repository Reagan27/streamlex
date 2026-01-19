<?php

namespace Vanguard\Repositories\Visualization;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VisualizationRepository
{
    /**
     * Get all visualizations or paginated visualizations if pagination is required.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Find a visualization by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;

    /**
     * Create a new visualization.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * Update an existing visualization.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed;

    /**
     * Delete a visualization by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
