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

    public function update(UpdateSemesterRequest $request, $id)
    {

    }

    public function destroy($id)
    {

    }




}
