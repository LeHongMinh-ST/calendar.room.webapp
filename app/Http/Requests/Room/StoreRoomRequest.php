<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'room_id' => 'required|unique:rooms|max:10',
            'name' => 'required|unique:rooms|max:60',
            'computer_number' => 'required|numeric|min:10|max:100',
            'address' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên phòng máy',
            'room_id' => 'Mã phòng máy',
            'computer_number' => 'Số máy',
            'address' => 'Địa chỉ',
        ];
    }

}
