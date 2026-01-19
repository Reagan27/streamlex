<?php

namespace Vanguard\Repositories\IssuesCategory;

use Vanguard\IssuesCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentIssuesCategory implements IssuesCategoryRepository
{
    /**
     * Get all issue categories.
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function all(?int $perPage = null): LengthAwarePaginator|Collection
    {
        if ($perPage) {
            return IssuesCategory::paginate($perPage);
        }

        return IssuesCategory::all();
    }

    /**
     * Find an issue category by its ID.
     *
     * @param int $id
     * @return IssuesCategory|null
     */
    public function find(int $id): ?IssuesCategory
    {
        return IssuesCategory::find($id);
    }

    /**
     * Create a new issue category.
     *
     * @param array $data
     * @return IssuesCategory
     */
    public function create(array $data): IssuesCategory
    {
        return IssuesCategory::create($data);
    }

    /**
     * Update an existing issue category.
     *
     * @param int $id
     * @param array $data
     * @return IssuesCategory
     */
    public function update(int $id, array $data): IssuesCategory
    {
        $category = $this->find($id);

        if ($category) {
            $category->update($data);
        }

        return $category;
    }

    /**
     * Delete an issue category by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $category = $this->find($id);
        return $category ? $category->delete() : false;
    }
}
