<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\UserResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * ルーム内ユーザ一覧取得
     *
     * @param IndexRequest $request
     * @param Room $room
     * @return AnonymousResourceCollection
     */
    public function index(IndexRequest $request, Room $room):AnonymousResourceCollection
    {
        $joinUsers = $room->members()->get();
        return UserResource::collection($joinUsers);
    }
}
