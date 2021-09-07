<?php

namespace App\Http\Requests\Comment;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var Room $room */
        $room = $this->route('room');
        $existUserInRoomMember = $room->members()->wherePivot('user_id', auth()->id())->exists();
        return $existUserInRoomMember;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|string|max:255'
        ];
    }
}
