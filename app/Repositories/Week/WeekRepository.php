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


    public function createBySemester($data, $semester): array
    {
        $date = 7 - date("N", $data['semester_start_date']);
        $weeks = [];
        for ($i = 1; $i <= $data['number_weeks']; $i++) {
            if ($i == 1) {
                $startDate = 0;
                $endDate = $date;
            } else {
                $startDate = $date + ($i - 2) * 7 + 1;
                $endDate = $date + ($i - 1) * 7;
            }
            $startDayTimestamp = strtotime($semester->semester_start_date . " + $startDate days");
            $endDayTimestamp = strtotime($semester->semester_start_date . " + $endDate days");

            $dataWeek = [];
            $dataWeek['semester_id'] = $semester->id;
            $dataWeek['week'] = $i;
            $dataWeek['start_day'] = $this->processDate($startDayTimestamp);
            $dataWeek['end_day'] = $this->processDate($endDayTimestamp);
            $weeks[] = $this->create($dataWeek);
        }

        return $weeks;
    }

    private function processDate($dateTimestamp)
    {
        return strftime("%Y-%m-%d", $dateTimestamp);
    }
}
