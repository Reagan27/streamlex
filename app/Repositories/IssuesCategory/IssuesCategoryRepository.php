<?php

namespace Vanguard\Repositories\IssuesCategory;

use Vanguard\IssuesCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IssuesCategoryRepository
{
    /**
     * Get all issue categories or paginated if necessary.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Find an issue category by its ID.
     *
     * @param int $id
     * @return IssuesCategory|null
     */
    public function find(int $id): ?IssuesCategory;

    /**
     * Create a new issue category.
     *
     * @param array $data
     * @return IssuesCategory
     */
    public function create(array $data): IssuesCategory;

    /**
     * Update an existing issue category.
     *
     * @param int $id
     * @param array $data
     * @return IssuesCategory
     */
    public function update(int $id, array $data): IssuesCategory;

    /**
     * Delete an issue category by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
