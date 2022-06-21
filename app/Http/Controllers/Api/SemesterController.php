<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\StoreSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Repositories\Semester\SemesterRepositoryInterface;
use App\Repositories\Week\WeekRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterController extends Controller
{
    use ResponseTrait;

    private $semesterRepository;
    private $weekRepository;

    public function __construct(
        SemesterRepositoryInterface $semesterRepository,
        WeekRepositoryInterface     $weekRepository
    )
    {
        $this->semesterRepository = $semesterRepository;
        $this->weekRepository = $weekRepository;
    }

    public function index(Request $request)
    {

    }

    public function store(StoreSemesterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $dateSemester = $this->processSemesterStartDate($data['semester_start_date'], $data['number_weeks']);

            $data['semester_start_date'] = $dateSemester['start'];
            $data['semester_end_date'] = $dateSemester['end'];;
            $semester = $this->semesterRepository->create($data);

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
                $weeks[] = $this->weekRepository->create($dataWeek);
            }
            DB::commit();

            return $this->responseSuccess([
                'semester' => $semester,
                'weeks' => $weeks
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error store semester', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateSemesterRequest $request, $id)
    {

    }

    public function destroy($id)
    {

    }

    private function processDate($dateTimestamp)
    {
        return strftime("%Y-%m-%d", $dateTimestamp);
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
