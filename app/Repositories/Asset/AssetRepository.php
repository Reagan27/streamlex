<?php

namespace Vanguard\Repositories\Asset;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Asset;

interface AssetRepository
{
    /**
     * Get all assets or paginated assets if pagination is required.
     *
     * @param int|null $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function all(?int $perPage = null);

    /**
     * Find an asset by its ID.
     *
     * @param int $id
     * @return Asset|null
     */
    public function find(int $id): ?Asset;

    /**
     * Create a new asset.
     *
     * @param array $data
     * @return Asset
     */
    public function create(array $data): Asset;

    /**
     * Update an existing asset.
     *
     * @param int $id
     * @param array $data
     * @return Asset
     */
    public function update(int $id, array $data): Asset;

    /**
     * Delete an asset by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Bulk update assets based on a condition.
     *
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function bulkUpdate(array $condition, array $data): bool;
}
