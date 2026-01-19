<?php

namespace Vanguard\Repositories\Support;

use Vanguard\SupportIssue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentSupport implements SupportRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection
    {
        if ($perPage) {
            return SupportIssue::paginate($perPage);
        }

        return SupportIssue::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?SupportIssue
    {
        return SupportIssue::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): SupportIssue
    {
        return SupportIssue::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): SupportIssue
    {
        $issue = $this->find($id);
        if ($issue) {
            $issue->update($data);
        }
        return $issue;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $issue = $this->find($id);
        return $issue ? $issue->delete() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function bulkUpdate(array $condition, array $data): bool
    {
        return SupportIssue::where($condition)->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getByType(string $type, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = SupportIssue::where('type', $type);

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getHighPriority(?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = SupportIssue::where('priority', 'High');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

        /**
     * Get issues by category ID.
     *
     * @param int $categoryId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByCategory(int $categoryId, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = SupportIssue::where('category_id', $categoryId);

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getUnresolved(?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = SupportIssue::where('status', 'Unresolved');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
