<?php

namespace Vanguard\Repositories\Message;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Message;

interface MessageRepository
{
    /**
     * Get all messages or paginated messages if pagination is required.
     *
     * @param int|null $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function all(?int $perPage = null);

    /**
     * Find a message by its ID.
     *
     * @param int $id
     * @return Message|null
     */
    public function find(int $id): ?Message;

    /**
     * Create a new message.
     *
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message;

    /**
     * Update an existing message.
     *
     * @param int $id
     * @param array $data
     * @return Message
     */
    public function update(int $id, array $data): Message;

    /**
     * Delete a message by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Bulk update messages based on a condition.
     *
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function bulkUpdate(array $condition, array $data): bool;
}
