<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\StoreCalendarEventRequest;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    use ResponseTrait;

    private $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function store(StoreCalendarEventRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $schedule = $this->scheduleRepository->create($data);
            return $this->responseSuccess(['schedule' => $schedule]);

        } catch (\Exception $exception) {
            Log::error('Error store schedule', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}
