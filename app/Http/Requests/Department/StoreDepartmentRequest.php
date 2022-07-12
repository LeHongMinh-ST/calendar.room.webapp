<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\BaseRequest;

class StoreDepartmentRequest extends BaseRequest
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
            'department_id' => 'required|unique:departments',
            'name' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Tên bộ môn',
            'department_id' => 'Mã bộ môn'
        ];
    }
}
