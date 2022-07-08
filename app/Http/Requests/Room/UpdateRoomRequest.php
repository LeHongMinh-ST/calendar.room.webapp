<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'room_id' => 'required|unique:rooms,room_id,' . $this->id . ',id|max:10',
            'name' => 'required|unique:rooms,name,' . $this->id . ',id|max:60',
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
