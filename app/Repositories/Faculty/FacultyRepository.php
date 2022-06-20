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

    public function getFacultyFilters(array $filters, array $relations = [])
    {
        $this->model = $this->originalModel;
        $paginate = config('constants.limit_of_paginate', 10);
        $orderBy = $filters['order_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $search = $filters['q'] ?? '';
        $paginate = (int)($filters['per_page'] ?? $paginate);

        return $this->model
            ->search($search)
            ->orderBy($orderBy, $order)
            ->with($relations)
            ->paginate($paginate);
    }

    public function create(array $payload)
    {
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();

        return parent::create($payload); // TODO: Change the autogenerated stub
    }

    public function updateById(int $id, array $payload)
    {
        $payload['user_update_id'] = auth()->id();
        return parent::updateById($id, $payload);
    }
}