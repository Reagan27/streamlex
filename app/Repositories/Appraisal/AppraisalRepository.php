<?php

namespace Vanguard\Repositories\Appraisal;

use Illuminate\Database\Eloquent\Collection;
use Vanguard\Appraisal;

interface AppraisalRepository
{
    /**
     * Get all appraisals.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find an appraisal by its ID.
     *
     * @param int $id
     * @return Appraisal|null
     */
    public function find(int $id): ?Appraisal;

    /**
     * Create a new appraisal.
     *
     * @param array $data
     * @return Appraisal
     */
    public function create(array $data): Appraisal;

    /**
     * Update an existing appraisal.
     *
     * @param int $id
     * @param array $data
     * @return Appraisal
     */
    public function update(int $id, array $data): Appraisal;

    /**
     * Delete an appraisal.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get appraisals for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getAppraisalsForUser(int $userId): Collection;

    /**
     * Get appraisals for users in specific counties.
     *
     * @param array $countyIds
     * @return Collection
     */
    public function getAppraisalsByCounties(array $countyIds): Collection;

    /**
     * Count total appraisals.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Get a query builder instance for the appraisal model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();
}