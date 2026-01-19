<?php

namespace Vanguard\Repositories\Email;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Email;

class EloquentEmail implements EmailRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(?int $perPage = null)
    {
        if ($perPage) {
            return Email::paginate($perPage);
        }

        return Email::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Email
    {
        return Email::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Email
    {
        return Email::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Email
    {
        $email = $this->find($id);
        if ($email) {
            $email->update($data);
        }
        return $email;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $email = $this->find($id);
        return $email ? $email->delete() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function bulkUpdate(array $condition, array $data): bool
    {
        return Email::where($condition)->update($data);
    }
}
