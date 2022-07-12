<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'room_id' => 'bail|required',
            'semester_id' => 'bail|required',
            'subject_group' => 'bail|required|numeric',
            'class' => 'bail|required',
            'amount_people' => 'bail|required|numeric',
            'day' => 'bail|required|numeric',
            'session' => 'bail|required|numeric|min:1|max:13',
            'number_session' => 'bail|required|numeric|min:1|max:5',
            'week' => 'bail|required|numeric|min:1',
            'number_week' => 'bail|required|numeric|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'room_id' => 'Phòng máy',
            'semester_id' => 'Môn học',
            'subject_group' => 'Nhóm môn học',
            'class' => 'Lớp',
            'amount_people' => 'Sĩ số',
            'day' => 'Thứ',
            'session' => 'Tiết bắt đầu',
            'number_session' => 'Số tiết',
            'week' => 'Tuần bắt đầu',
            'number_week' => 'Số tuần',
        ];
    }
}
