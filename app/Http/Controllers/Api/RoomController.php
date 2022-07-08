<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Room\RoomRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    use ResponseTrait;

    private $roomRepository;
    private $assignmentRepository;

    public function __construct(
        RoomRepositoryInterface       $roomRepository,
        AssignmentRepositoryInterface $assignmentRepository
    )
    {
        $this->roomRepository = $roomRepository;
        $this->assignmentRepository = $assignmentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $rooms = $this->roomRepository->getFilters($data, []);
        return $this->responseSuccess(['rooms' => $rooms]);
    }

    public function show($id): JsonResponse
    {
        $room = $this->roomRepository->findById($id);

        return $this->responseSuccess([
            'room' => $room
        ]);
    }

    public function store(StoreRoomRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $room = $this->roomRepository->create($data);
            return $this->responseSuccess(['room' => $room]);
        } catch (\Exception $exception) {
            Log::error('Error store room', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateRoomRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $this->roomRepository->update($data, $id);
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
        $assigment = $this->assignmentRepository->getFirstBy(['room_id', $id]);

        if ($assigment) {
            $message = 'Phòng máy đang được sử dụng!';
            return $this->responseError($message);
        }

        if ($this->roomRepository->deleteById($id)) {
            return $this->responseSuccess();
        }

        return $this->responseError();
    }
}
