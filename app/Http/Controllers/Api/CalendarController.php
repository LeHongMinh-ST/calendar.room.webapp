<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Models\Week;
use App\Repositories\Room\RoomRepositoryInterface;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Repositories\Semester\SemesterRepositoryInterface;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Week\WeekRepositoryInterface;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CalendarController extends Controller
{
    use ResponseTrait;

    private $scheduleRepository;
    private $roomRepository;
    private $weekRepository;
    private $subjectRepository;
    private $userRepository;
    private $semesterRepository;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        RoomRepositoryInterface     $roomRepository,
        WeekRepositoryInterface     $weekRepository,
        SubjectRepositoryInterface  $subjectRepository,
        UserRepositoryInterface     $userRepository,
        SemesterRepositoryInterface $semesterRepository
    )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->roomRepository = $roomRepository;
        $this->weekRepository = $weekRepository;
        $this->subjectRepository = $subjectRepository;
        $this->userRepository = $userRepository;
        $this->semesterRepository = $semesterRepository;
    }

    public function getEventsCalender(Request $request): JsonResponse
    {
        $this->handleGetView();
        $semesterId = Session::get('semesters')['id'];


        $roomId = $request->input('roomId');

        if (!$semesterId) {
            $semesterId = $this->getSemesterNow()->id;
        }

        if (!$roomId) {
            $firstRoom = Room::query()->where(['is_active' => 1])->first();
            $roomId = $firstRoom->id;
        }

        $schedules = Schedule::query()
            ->where('semester_id', $semesterId)
            ->where('room_id', $roomId)
            ->get()->toArray();

        $weeks = Week::query()->where('semester_id', $semesterId)->get();
        $events = [];
        foreach ($weeks as $week) {
            $events[] = [
                'name' => 'Tuần ' . $week->week,
                'start' => $week->start_day,
                'isEventWeek' => true
            ];
        }
        $subjects = $this->subjectRepository->all();


        foreach ($schedules as $schedule) {
            $arrayWeeks = explode(",", $schedule['week_check']);
            $arrayWeeks = $this->getChildArray($arrayWeeks);

            $teacher = $this->userRepository->getFirstBy(['user_name' => $schedule['teacher_id']]);
            foreach ($arrayWeeks as $arrayWeek) {
                foreach ($arrayWeek as $week) {
                    //Lấy các thông tin cho lớp học
                    $event['group'] = $schedule['subject_group'];
                    $event['class'] = $schedule['class'];
                    $event['amount_people'] = $schedule['amount_people'];
                    $event['teacher_id'] = $schedule['teacher_id'];
                    $event['teacher_name'] = $teacher ? $teacher['full_name'] : 'Chưa rõ';
                    $event['subject'] = $this->filterArray($subjects, $schedule['subject_id'])['name'];

                    $listWeek = explode(',', $schedule['week_check']);
                    $event['weeks'] = implode(', ', $listWeek);
                    $event['room_id'] = $roomId;
                    $event['subject_id'] = $this->filterArray($subjects, $schedule['subject_id'])['subject_id'];
                    $event['session'] = $schedule['session'] . "-" . ($schedule['session'] + $schedule['number_session'] - 1);

                    $event['name'] = $event['subject'];
                    $event['color'] = ($schedule['status'] == 1) ? '#4285f4' : '#F6BF26';

                    $datetime = $this->timeEvent($schedule, $week);
                    $event['start'] = $datetime['start'];
                    $event['end'] = $datetime['end'];
                    if ($schedule['day'] == 8)
                        $event['daysOfWeek'] = [0];
                    else
                        $event['daysOfWeek'] = [$schedule['day'] - 1];
                    $event['schedule'] = $schedule;
                    $events[] = $event;
                }
            }
        }

        return $this->responseSuccess([
            'events' => $events
        ]);
    }

    private function getSemesterNow()
    {
        $today = date("Y-m-d");
        $semesters = $this->scheduleRepository->all();
        foreach ($semesters as $semester) {
            if (strtotime($today) >= strtotime($semester->semester_start_date) && strtotime($today) <= strtotime($semester->semester_end_date)) {
                return $semester;
            }
        }
        return null;
    }

    private function getChildArray(array $data): array
    {
        $stack = [];
        $start = 0;

        for ($i = 0; $i < count($data) - 1; $i++) {
            if ($data[$i + 1] - $data[$i] > 1) {
                $stack[] = array_slice($data, $start, $i - $start + 1);
                $start = $i + 1;
            }
        }

        $stack[] = array_slice($data, $start);
        return $stack;
    }

    public function filterArray($array, $id)
    {
        foreach ($array as $value) {
            if (isset($value['id'])) {
                if ($value['id'] == $id) {
                    return $value;
                }
            }
        }
        return null;
    }

    public function timeEvent($schedule, $dataWeek): array
    {
        $week = $this->weekRepository->getFirstBy([
            'semester_id' => $schedule['semester_id'],
            'week' => $dataWeek
        ]);


        //Lấy ra ngày bắt đầu môn học
        if ($schedule)
            $day = $schedule['day'] - 2;
        $date = Carbon::make($week['start_day'])->addDays($day)->format('Y-m-d');
        //Lấy ra thời gian bắt đầu và thời gian kết thúc
        $setTime = config('settime');
        $startTime = $setTime[$schedule['session']]['start'];
        $endTime = $setTime[$schedule['session'] + $schedule['number_session'] - 1]['end'];
        $datetime['start'] = $date . ' ' . $startTime;
        $datetime['end'] = $date . ' ' . $endTime;
        return $datetime;
    }

    public function handleGetView()
    {
        if (!Session::has('semesters')) {
            $today = date("Y-m-d");
            $semesters = $this->semesterRepository->all();
            foreach ($semesters as $semester) {
                if (strtotime($today) >= strtotime($semester['semester_start_date']) && strtotime($today) <= strtotime($semester['semester_end_date'])) {
                    Session::put('semesters', $semester);
                    Session::put('semester_now', $semester);
                }
            }
        }
        if (!Session::has('room')) {
            $room = $this->roomRepository->getFirstBy(['room_id' => 'THCNTT01']);
            if (!empty($room)) {
                $room = $room->toArray();
                Session::put('room', $room);
            }
        }

        //Xử lý các thời khóa biểu đã hết hạn xác nhận
        $schedules = $this->scheduleRepository->allBy(['status' => 0]);
        foreach ($schedules as $schedule) {
            $day = $schedule->day - 2;

            $weekDate = $this->weekRepository->getFirstBy([
                'semester_id' => Session::get('semester_now')['id'],
                'week' => $schedule->week
            ]);

            $dateStart = date('Y-m-d', strtotime($weekDate['start_day'] . ' + ' . $day . ' days'));
            $dateNow = date('Y-m-d');

            if (strtotime($dateStart) < strtotime($dateNow)) {
                $data['status'] = 2;
                $this->scheduleRepository->updateById($schedule->id,$data);
                $this->scheduleRepository->deleteById($schedule->id);
            }
        }

    }
}
