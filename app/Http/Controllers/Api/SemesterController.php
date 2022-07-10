<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Semester\StoreSemesterRequest;
use App\Http\Requests\Semester\UpdateSemesterRequest;
use App\Models\Semester;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Semester\SemesterRepositoryInterface;
use App\Repositories\Week\WeekRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterController extends Controller
{
    use ResponseTrait;

    private $semesterRepository;
    private $weekRepository;
    private $assignmentRepository;

    public function __construct(
        SemesterRepositoryInterface   $semesterRepository,
        WeekRepositoryInterface       $weekRepository,
        AssignmentRepositoryInterface $assignmentRepository
    )
    {
        $this->semesterRepository = $semesterRepository;
        $this->weekRepository = $weekRepository;
        $this->assignmentRepository = $assignmentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $semesters = $this->semesterRepository->getFilters($data, ['weeks']);
        return $this->responseSuccess(['semesters' => $semesters]);
    }

    public function store(StoreSemesterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $semester = $this->semesterRepository->create($data);
            $weeks = $this->weekRepository->createBySemester($data, $semester);
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

    public function update(UpdateSemesterRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            if (!$this->semesterRepository->checkTime($id, $data)) {
                $message = 'error';
                $data = [
                  'semester_start_date' => [
                      'Ngày bắt đầu học kỳ đã tồn tại'
                  ]
                ];
                return $this->responseError($message, $data);
            }

            $schoolYear = $this->semesterRepository->findById($id);

            $semester = $this->semesterRepository->getFirstBy([
                'semester' => $data['semester'],
                'school_year' => $schoolYear->school_year,
                ['id', '<>', $id]
            ]);

            if ($semester) {
                $message = 'Học kỳ đã tồn tại!';
                return $this->responseError($message);
            }

            $this->semesterRepository->updateById($id, $data);

            $semester = $this->semesterRepository->findById($id);

            $this->weekRepository->deleteBy(['semester_id' => $id]);

            $weeks = $this->weekRepository->createBySemester($data, $semester);
            DB::commit();

            return $this->responseSuccess([
                'weeks' => $weeks
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update semester', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        $assigment = $this->assignmentRepository->getFirstBy(['semester_id' => $id]);
        if ($assigment) {
            $message = 'Học kỳ đang được sử dụng. Không thế xóa học kỳ!';
            return $this->responseError($message);
        }

        if ($this->semesterRepository->deleteById($id)) {
            $this->weekRepository->deleteBy(['semester_id' => $id]);
            return $this->responseSuccess();
        }

        return $this->responseError();
    }

    public function getWeekBySemesterId(Request $request, $id): JsonResponse
    {
        $data = $request->all();
        $weeks = $this->weekRepository->getBySemesterId($id, $data);
        return $this->responseSuccess(['weeks' => $weeks]);
    }


}
