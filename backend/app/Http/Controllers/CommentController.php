<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\IndexRequest;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    /**
     * コメント一覧取得
     *
     * @param IndexRequest $request
     * @param Room $room
     * @return AnonymousResourceCollection
     */
    public function index(IndexRequest $request, Room $room): AnonymousResourceCollection
    {
        $validated = $request->validated();
        if (array_key_exists('time', $validated)) {
            $targetTime = new Carbon($validated['time']);
            $comments = $room->comments()
                ->where('created_at', '>', $targetTime)->orderBy('created_at')->get();
        } else {
            $comments = $room->comments()
                ->orderBy('created_at')->get();
        }

        return CommentResource::collection($comments);
    }

    /**
     * コメント投稿
     *
     * @param StoreRequest $request
     * @param Room $room
     * @return CommentResource
     */
    public function store(StoreRequest $request, Room $room): CommentResource
    {
        $content = $request->validated()['content'];

        /** @var Comment $comment */
        $comment = Comment::make([
            'content' => $content
        ]);
        $comment->user()->associate(auth()->user());
        $room->comments()->save($comment);

        return new CommentResource($comment);
    }
}
