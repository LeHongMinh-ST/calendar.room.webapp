<?php

namespace App\Repositories\Week;

use App\Models\Week;
use App\Repositories\Base\BaseRepository;

class WeekRepository extends BaseRepository implements WeekRepositoryInterface
{
    public function __construct(Week $model)
    {
        parent::__construct($model);
    }

    public function create(array $payload)
    {
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();

        return parent::create($payload);
    }

    public function updateById(int $id, array $payload)
    {
        $payload['user_update_id'] = auth()->id();
        return parent::updateById($id, $payload);
    }
}
