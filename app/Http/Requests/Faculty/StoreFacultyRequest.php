<?php

namespace App\Http\Requests\Faculty;

use App\Http\Requests\BaseRequest;

class StoreFacultyRequest extends BaseRequest
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
            'faculty_id' => 'required|unique:faculties',
            'name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên khoa',
            'faculty_id' => 'Mã khoa'
        ];
    }
}
