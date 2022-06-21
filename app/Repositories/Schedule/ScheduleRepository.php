<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;
use App\Repositories\Base\BaseRepository;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }
}
