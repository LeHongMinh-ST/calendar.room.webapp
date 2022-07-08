<?php

namespace App\Http\Requests\Semester;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'number_weeks' => 'required|numeric|max:60|min:2',
            'semester_start_date' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'number_weeks' => 'Số tuần',
            'semester_start_date' => 'Ngày bắt đầu',
        ];
    }
}
