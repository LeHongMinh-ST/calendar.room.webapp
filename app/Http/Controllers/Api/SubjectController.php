<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    use ResponseTrait;

    private $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $subjects= $this->subjectRepository->getFilters($data, ['department']);
        return $this->responseSuccess(['subjects' => $subjects]);
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $subject = $this->subjectRepository->create($data);
            return $this->responseSuccess(['subject' => $subject]);
        } catch (\Exception $exception) {
            Log::error('Error store subject', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateSubjectRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $this->subjectRepository->updateById($id, $data);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update room', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        if ($this->subjectRepository->deleteById($id)) {
            return $this->responseSuccess();
        }
        return $this->responseError();
    }
}
