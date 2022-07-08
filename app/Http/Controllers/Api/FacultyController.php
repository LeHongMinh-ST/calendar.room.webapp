<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreFacultyRequest;
use App\Http\Requests\Faculty\UpdateFacultyRequest;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Faculty\FacultyRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class FacultyController extends Controller
{
    use ResponseTrait;

    private $facultyRepository;
    private $departmentRepository;

    public function __construct(
        FacultyRepositoryInterface    $facultyRepository,
        DepartmentRepositoryInterface $departmentRepository
    )
    {
        $this->facultyRepository = $facultyRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $faculties = $this->facultyRepository->getFilters($data, ['departments']);
        return $this->responseSuccess(['faculties' => $faculties]);
    }

    public function store(StoreFacultyRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $faculty = $this->facultyRepository->create($data);
            return $this->responseSuccess(['faculty' => $faculty]);
        } catch (\Exception $exception) {
            Log::error('Error store faculty', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateFacultyRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $this->facultyRepository->updateById($id, $data);
            return $this->responseSuccess();

        } catch (\Exception $exception) {
            Log::error('Error update faculty', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        if ($this->facultyRepository->deleteById($id)) {
            $this->departmentRepository->deleteBy(['faculty_id' => $id]);
            return $this->responseSuccess();
        }

        return $this->responseError();
    }
}
