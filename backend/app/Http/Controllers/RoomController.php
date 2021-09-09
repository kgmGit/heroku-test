<?php

namespace App\Http\Controllers;

use App\Http\Requests\Room\DestroyRequest;
use App\Http\Requests\Room\IndexRequest;
use App\Http\Requests\Room\JoinRequest;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RoomController extends Controller
{
    /**
     * 部屋一覧取得
     *
     * @param IndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $user_id = $request->query('user-id');
        if ($user_id) {
            /** @var User $user */
            $user = User::find($user_id);
            assert($user !== null);
            $rooms = $user->ownRooms()->get();
        } else {
            $rooms = Room::all();
        }
        return RoomResource::collection($rooms);
    }

    /**
     * ルーム作成
     *
     * @param StoreRequest $request
     * @return RoomResource
     */
    public function store(StoreRequest $request): RoomResource
    {
        $validated = $request->validated();
        if (array_key_exists('password', $validated)) {
            $validated['password'] = Hash::make($validated['password']);
        }
        $room = new Room($validated);
        $room->owner()->associate(auth()->user());
        $room->save();
        return new RoomResource($room);
    }

    /**
     * 部屋名変更
     *
     * @param UpdateRequest $request
     * @param Room $room
     * @return RoomResource
     */
    public function update(UpdateRequest $request, Room $room): RoomResource
    {
        $room->name = $request->validated()['name'];
        $room->save();
        return new RoomResource($room);
    }

    /**
     * ルーム削除
     *
     * @param DestroyRequest $request
     * @param Room $room
     * @return JsonResponse
     */
    public function destroy(DestroyRequest $request, Room $room): JsonResponse
    {
        $room->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function join(JoinRequest $request, Room $room): JsonResponse
    {
        if ($room->password) {
            $plainPassword = $request->validated()['password'] ?? null;

            if (!Hash::check($plainPassword, $room->password)) {
                logger()->debug('plain : ' . $plainPassword);
                logger()->debug('room : ' . $room->password);
                return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        /** @var User $user */
        $user = auth()->user();
        $isRoomBelongsToUser = $user->joiningRooms()
            ->wherePivot('room_id', $room->id)
            ->exists();

        if ($isRoomBelongsToUser) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            $user->joiningRooms()->syncWithoutDetaching([$room->id]);
            return response()->json(null, Response::HTTP_CREATED);
        }
    }
}
