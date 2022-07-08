<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\Base\BaseRepository;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
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