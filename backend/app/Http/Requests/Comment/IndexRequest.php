<?php

namespace App\Http\Requests\Comment;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
        return $room->id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time' => 'sometimes|date_format:Y-m-d H:i:s',
        ];
    }
}
