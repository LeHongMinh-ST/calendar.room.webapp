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
        $startDate = Carbon::make($data['semester_start_date'])->startOfWeek();
        $weeks = [];
        for ($i = 1; $i <= $data['number_weeks']; $i++) {
            $start = Carbon::create($startDate);
            $end = $startDate->addDays(6);
            $dataWeek = [];
            $dataWeek['semester_id'] = $semester->id;
            $dataWeek['week'] = $i;
            $dataWeek['start_day'] = $start->format('Y-m-d');
            $dataWeek['end_day'] = $end->format('Y-m-d');
            $weeks[] = $this->create($dataWeek);
            $startDate->addDay();

        }

        return $weeks;
    }

    public function getBySemesterId($id, $filter = [], $relations = [])
    {
        $paginate = config('constants.limit_of_paginate', 10);
        $orderBy = $filters['order_by'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $paginate = (int)($filters['per_page'] ?? $paginate);

        return $this->model
            ->where('semester_id', $id)
            ->orderBy($orderBy, $order)
            ->with($relations)
            ->paginate($paginate);
    }

    private function processDate($dateTimestamp)
    {
        return strftime("%Y-%m-%d", $dateTimestamp);
    }
}
