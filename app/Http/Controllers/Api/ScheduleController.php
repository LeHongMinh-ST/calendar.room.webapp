<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\StoreCalendarEventRequest;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Week\WeekRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ScheduleController extends Controller
{
    use ResponseTrait;

    private $scheduleRepository;
    private $weekRepository;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        WeekRepository              $weekRepository
    )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->weekRepository = $weekRepository;
    }

    public function index(Request $request): JsonResponse
    {
        return $this->responseSuccess();
    }

    public function show(): JsonResponse
    {
        return $this->responseSuccess();
    }

    public function store(StoreCalendarEventRequest $request): JsonResponse
    {
        try {
            $data = $request->all();

            if (!$this->checkLesson($data['session'], $data['number_session'])) {
                return $this->responseError();
            }

            if (!$this->checkMaxWeekBackend($data['week'])) {
                return $this->responseError();
            }

            if (!$this->checkWeekSemesterBackend($data['week'], $data['number_week'])) {
                return $this->responseError();
            }

            if (!$this->checkDayStartBackend($data['day'], $data['week'])) {
                return $this->responseError();
            }

            if (!$this->checkUniqueSchedulesBackend($data['room_id'], $data['session'], $data['day'], $data['week'])) {
                return $this->responseError();
            }

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

    public function update(): JsonResponse
    {
        return $this->responseSuccess();
    }

    public function destroy($id): JsonResponse
    {
        return $this->responseSuccess();
    }

    private function checkLesson($lessonStart, $lesson): bool
    {
        if ($lessonStart <= 5)
            return $lesson <= 5 - $lessonStart + 1;
        else if ($lessonStart <= 10)
            return $lesson <= 11 - $lessonStart;
        else
            return $lesson <= 14 - $lessonStart;
    }

    private function checkMaxWeekBackend($weekStart): bool
    {
        return !($weekStart > Session::get('semester_now')['number_weeks']);
    }

    private function checkWeekSemesterBackend($weekStart, $weekQuantity): bool
    {
        $weekNumber = $weekStart + $weekQuantity - 1;

        return !($weekNumber > Session::get('semester_now')['number_weeks']);
    }

    private function checkDayStartBackend($weekDay, $weekStart): bool
    {

        $day = $weekDay - 2;

        $weekDay = $this->weekRepository->getFirstBy([
            'semester_id' => Session::get('semester_now')['id'],
            'week' => $weekStart
        ]);

        $dateStart = date('Y-m-d', strtotime($weekDay['start_day'] . ' + ' . $day . ' days'));
        $dateNow = date('Y-m-d');

        return strtotime($dateStart) >= strtotime($dateNow);
    }

    private function checkUniqueSchedulesBackend($room, $lessonStart, $weekDay, $weekStart): bool
    {
        $schedule = $this->scheduleRepository->getFirstBy([
            'semester_id' => Session::get('semester_now')['id'],
            'room_id' => $room,
            'session' => $lessonStart,
            'status' => '1',
            'day' => $weekDay,
            'week' => $weekStart,
        ]);

        return empty($schedule);
    }
}
