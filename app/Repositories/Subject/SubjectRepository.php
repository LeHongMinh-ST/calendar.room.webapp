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
}
