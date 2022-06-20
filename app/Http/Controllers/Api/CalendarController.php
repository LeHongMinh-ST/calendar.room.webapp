<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Models\Week;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    use ResponseTrait;

    public function getEventsCalender(Request $request): JsonResponse
    {
        $semesterId = $request->input('semesterId');
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
        $subjects = Subject::query()->get();


        foreach ($schedules as $schedule) {
            $arrayWeeks = explode(",", $schedule['week_check']);
            $arrayWeeks = $this->getChildArray($arrayWeeks);

            $teacher = User::query()->where(['user_name' => $schedule['teacher_id']])->first();
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
        $semesters = Semester::query()->get();
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
        $week = Week::query()
            ->where('semester_id', $schedule['semester_id'])
            ->where('week', $dataWeek)
            ->first();

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
}
