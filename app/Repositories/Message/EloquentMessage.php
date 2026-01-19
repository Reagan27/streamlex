<?php

namespace Vanguard\Repositories\Message;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Vanguard\Message;

class EloquentMessage implements MessageRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(?int $perPage = null)
    {
        if ($perPage) {
            return Message::paginate($perPage);
        }

        return Message::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Message
    {
        return Message::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Message
    {
        $message = $this->find($id);
        if ($message) {
            $message->update($data);
        }
        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        $message = $this->find($id);
        return $message ? $message->delete() : false;
    }

    /**
     * {@inheritdoc}
     */
    public function bulkUpdate(array $condition, array $data): bool
    {
        return Message::where($condition)->update($data);
    }
}
