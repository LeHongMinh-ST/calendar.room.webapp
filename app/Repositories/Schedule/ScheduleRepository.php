<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;
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

    private function checkLesson($lessonStart, $lesson): bool
    {
        if ($lessonStart <= 5)
            return $lesson <= 5 - $lessonStart + 1;
        else if ($lessonStart <= 10)
            return $lesson <= 11 - $lessonStart;
        else
            return $lesson <= 14 - $lessonStart;
    }

    private function checkMaxWeekBackend($weekStart): bool
    {
        return !($weekStart > Session::get('semester_now')['number_weeks']);
    }
}
