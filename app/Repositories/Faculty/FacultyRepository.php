<?php

namespace App\Repositories\Faculty;

use App\Models\Faculty;
use App\Repositories\Base\BaseRepository;

class FacultyRepository extends BaseRepository implements FacultyRepositoryInterface
{
    public function __construct(Faculty $model)
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
