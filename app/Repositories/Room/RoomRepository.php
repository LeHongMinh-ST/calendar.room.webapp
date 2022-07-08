<?php

namespace App\Repositories\Room;

use App\Models\Room;
use App\Repositories\Base\BaseRepository;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function create(array $payload)
    {
        $payload = $this->getRoomPayload($payload);
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();
        return parent::create($payload);
    }

    public function updateById(int $id, array $payload)
    {
        $payload = $this->getRoomPayload($payload);
        $payload['user_update_id'] = auth()->id();
        return parent::updateById($id, $payload);
    }

    private function getRoomPayload(array $payload): array
    {
        $payload['room_id'] = strtoupper($payload['room_id']);
        $payload['name'] = ucfirst($payload['name']);
        $payload['address'] = ucfirst($payload['address']);
        $payload['software'] = ucfirst($payload['software']);
        $payload['subjects'] = implode(",", $payload['subjects']);
        return $payload;
    }
}
