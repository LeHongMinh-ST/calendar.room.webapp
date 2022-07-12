<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;
use App\Models\Week;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Session;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    public function create(array $payload)
    {
        $payload['week_check'] = implode(',', $payload['week_check'] ?? []);
        $payload['teacher_id'] = auth()->user()->user_name;
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();
        return parent::create($payload);
    }

}
