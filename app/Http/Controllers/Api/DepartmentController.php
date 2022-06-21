<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    use ResponseTrait;

    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $departments = $this->departmentRepository->getDepartmentFilters($data);
        return $this->responseSuccess(['departments' => $departments]);
    }


    public function getListByFacultyId($facultyId): JsonResponse
    {
        $departments = $this->departmentRepository->allBy(['faculty_id' => $facultyId]);
        return $this->responseSuccess(['departments' => $departments]);
    }


    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $department = $this->departmentRepository->create($data);
            return $this->responseSuccess(['department' => $department]);
        } catch (\Exception $exception) {
            Log::error('Error store department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateDepartmentRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $this->departmentRepository->updateById($id, $data);
            return $this->responseSuccess();

        } catch (\Exception $exception) {
            Log::error('Error update department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        if ($this->departmentRepository->deleteById($id)) {
            return $this->responseSuccess();
        }

        return $this->responseError();
    }
}
