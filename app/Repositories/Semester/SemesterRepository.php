<?php

namespace App\Repositories\Semester;

use App\Models\Semester;
use App\Repositories\Base\BaseRepository;

class SemesterRepository extends BaseRepository implements SemesterRepositoryInterface
{
    public function __construct(Semester $model)
    {
        parent::__construct($model);
    }

    public function create(array $payload)
    {
        $dateSemester = $this->processSemesterStartDate($payload['semester_start_date'], $payload['number_weeks']);
        $payload['semester_start_date'] = $dateSemester['start'];
        $payload['semester_end_date'] = $dateSemester['end'];
        $payload['user_create_id'] = auth()->id();
        $payload['user_update_id'] = auth()->id();

        return parent::create($payload);
    }

    public function updateById(int $id, array $payload)
    {
        $dateSemester = $this->processSemesterStartDate($payload['semester_start_date'], $payload['number_weeks']);
        $payload['semester_start_date'] = $dateSemester['start'];
        $payload['semester_end_date'] = $dateSemester['end'];
        $payload['user_update_id'] = auth()->id();
        return parent::updateById($id, $payload);
    }

    private function processSemesterStartDate($date, $numberWeek): array
    {
        $semesterStartDate = str_replace('/', '-', $date);
        $semesterStartDate = date("Y-m-d", strtotime($semesterStartDate));
        $countDay = date("N", strtotime($semesterStartDate));
        $semesterEndDate = strtotime($semesterStartDate . " + $numberWeek week - $countDay day");
        $semesterEndDate = strftime("%Y-%m-%d", $semesterEndDate);

        return [
            'start' => $semesterStartDate,
            'end' => $semesterEndDate,
        ];
    }
}
