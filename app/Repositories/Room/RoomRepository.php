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
}
