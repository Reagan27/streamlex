<?php

namespace Vanguard\Repositories\Asset;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Asset;

class EloquentAsset implements AssetRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(?int $perPage = null)
    {
        if ($perPage) {
            return Asset::paginate($perPage);
        }

        return Asset::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Asset
    {
        return Asset::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Asset
    {
        $asset = $this->find($id);
        if ($asset) {
            $asset->update($data);
        }
        return $asset;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $asset = $this->find($id);
        return $asset ? $asset->delete() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function bulkUpdate(array $condition, array $data): bool
    {
        return Asset::where($condition)->update($data);
    }
}
