<?php

namespace App\Repositories\Week;

use App\Models\Week;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

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

        $dayOfWeek = 7 - Carbon::make($data['semester_start_date'])->dayOfWeek;
        $weeks = [];
        for ($i = 1; $i <= $data['number_weeks']; $i++) {
            if ($i == 1) {
                $startDate = 0;
                $endDate = $dayOfWeek;
            } else {
                $startDate = (($dayOfWeek + ($i - 2)) * 7) + 1;
                $endDate = ($dayOfWeek + ($i - 1)) * 7;
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

    public function getBySemesterId($id, $filter = [], $relations = [])
    {
        $paginate = config('constants.limit_of_paginate', 10);
        $orderBy = $filters['order_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $search = $filters['q'] ?? '';
        $paginate = (int)($filters['per_page'] ?? $paginate);

        return $this->model
            ->whrere('id', $id)
            ->search($search)
            ->orderBy($orderBy, $order)
            ->with($relations)
            ->paginate($paginate);
    }

    private function processDate($dateTimestamp)
    {
        return strftime("%Y-%m-%d", $dateTimestamp);
    }
}
