<?php

namespace Vanguard\Repositories\Support;

use Vanguard\SupportIssue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SupportRepository
{
    /**
     * Get all support issues or paginated support issues if pagination is required.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Find a support issue by its ID.
     *
     * @param int $id
     * @return SupportIssue|null
     */
    public function find(int $id): ?SupportIssue;

    /**
     * Create a new support issue.
     *
     * @param array $data
     * @return SupportIssue
     */
    public function create(array $data): SupportIssue;

    /**
     * Update an existing support issue.
     *
     * @param int $id
     * @param array $data
     * @return SupportIssue
     */
    public function update(int $id, array $data): SupportIssue;

    /**
     * Delete a support issue by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Bulk update support issues based on a condition.
     *
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function bulkUpdate(array $condition, array $data): bool;

    /**
     * Get support issues by type (e.g., Incidence, Payment, Contracting, etc.).
     *
     * @param string $type
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByType(string $type, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get high-priority support issues.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getHighPriority(?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get issues by category ID.
     *
     * @param int $categoryId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByCategory(int $categoryId, ?int $perPage = null): LengthAwarePaginator|Collection;

    /**
     * Get unresolved support issues.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getUnresolved(?int $perPage = null): LengthAwarePaginator|Collection;
}
