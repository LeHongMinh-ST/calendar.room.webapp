<?php

namespace App\Repositories\Subject;

use App\Models\Subject;
use App\Repositories\Base\BaseRepository;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    public function create(array $payload)
    {
        $payload['subject_id'] = strtoupper($payload['subject_id']);
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();
        return parent::create($payload);
    }

    public function updateById(int $id, array $payload)
    {
        $payload['subject_id'] = strtoupper($payload['subject_id']);
        $payload['user_update_id'] = auth()->id();
        return parent::updateById($id, $payload);
    }
}
