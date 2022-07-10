<?php

namespace App\Repositories\Assignment;

use App\Models\Assignment;
use App\Repositories\Base\BaseRepository;

class AssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $model)
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
