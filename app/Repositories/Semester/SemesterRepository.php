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

    public function checkTime($id, $data): bool
    {
        $status = true;
        $startDate = str_replace('/', '-', $data['semester_start_date']);

        //Tính ngày bắt đầu và ngày kết thúc của học kỳ
        $startDate = date("Y-m-d", strtotime($startDate));
        $countDay = date("N", strtotime($startDate)); //Lấy ra ngày trong tuần đầu tiên
        $endDate = strtotime($startDate . " + " . $data['number_weeks'] . " week - $countDay day");
        $endDate = strftime("%Y-%m-%d", $endDate);

        if ($id == null) {
            //Kiểm tra thời gian của học kỳ có bị trùng hay không khi thêm mới
            $checkDateSemester = $this->model->where(function ($q) use ($startDate) {
                $q->where('semester_start_date', '<=', $startDate)
                    ->where('semester_end_date', '>=', $startDate);
            })->orWhere(function ($q) use ($endDate, $startDate) {
                $q->where('semester_start_date', '>=', $startDate)
                    ->where('semester_start_date', '<=', $endDate);
            })->get();
        } else {
            //Kiểm tra thời gian của học kỳ có bị trùng hay không khi chỉnh sửa
            $checkDateSemester = $this->model->where(function ($q) use ($startDate, $id) {
                $q->where('semester_start_date', '<=', $startDate)
                    ->where('semester_end_date', '>=', $startDate)
                    ->where('id', '<>', $id);
            })->orWhere(function ($q) use ($startDate, $endDate, $id) {
                $q->where('semester_start_date', '>=', $startDate)
                    ->where('semester_start_date', '<=', $endDate)
                    ->where('id', '<>', $id);
            })->get();
        }

        if (count($checkDateSemester) > 0) {
            $status = false;
        }
        return $status;
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
