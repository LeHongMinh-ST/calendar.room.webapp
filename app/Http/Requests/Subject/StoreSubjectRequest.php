<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
            'subject_id' => 'required|unique:subjects|min:4|max:15',
            'name' => 'required|min:3|max:100',
            'department_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên môn học',
            'department_id' => 'Mã bộ môn',
            'subject_id' => 'Mã môn học',
        ];
    }
}
