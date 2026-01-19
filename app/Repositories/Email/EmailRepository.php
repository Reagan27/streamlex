<?php

namespace Vanguard\Repositories\Email;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Email;

interface EmailRepository
{
    /**
     * Get all emails or paginated emails if pagination is required.
     *
     * @param int|null $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function all(?int $perPage = null);

    /**
     * Find an email by its ID.
     *
     * @param int $id
     * @return Email|null
     */
    public function find(int $id): ?Email;

    /**
     * Create a new email.
     *
     * @param array $data
     * @return Email
     */
    public function create(array $data): Email;

    /**
     * Update an existing email.
     *
     * @param int $id
     * @param array $data
     * @return Email
     */
    public function update(int $id, array $data): Email;

    /**
     * Delete an email by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Bulk update emails based on a condition.
     *
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function bulkUpdate(array $condition, array $data): bool;
}
