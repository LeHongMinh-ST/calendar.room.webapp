<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\StoreFacultyRequest;
use App\Http\Requests\Faculty\UpdateFacultyRequest;
use App\Repositories\Faculty\FacultyRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class FacultyController extends Controller
{
    use ResponseTrait;

    private $facultyRepository;

    public function __construct(FacultyRepositoryInterface $facultyRepository)
    {
        $this->facultyRepository = $facultyRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $faculties = $this->facultyRepository->getFilters($data);
        return $this->responseSuccess([
            'faculties' => $faculties
        ]);
    }

    public function show($id): JsonResponse
    {
        return $this->responseSuccess([
            'faculty' => $this->facultyRepository->findById($id)
        ]);
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
            return $this->responseSuccess();
        }

        return $this->responseError();
    }
}
