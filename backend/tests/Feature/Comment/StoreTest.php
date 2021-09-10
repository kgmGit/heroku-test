<?php

namespace Tests\Feature\Comment;

use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->ownRooms()->saveMany(
            [
                Room::factory()->make(['name' => 'roomName']),
                Room::factory()->make(['name' => 'notJoinRoomName']),
            ]
        );
        $this->user->joiningRooms()->attach(
            Room::where('name', 'roomName')->first()->id
        );
    }

    public function test正常系_コメント投稿()
    {
        $body = [
            'content' => 'comment'
        ];
        $this->actingAs($this->user);
        $response = $this->json('post', 'api/rooms/roomName/comments', $body);

        $comment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->first();
        $this->assertEquals($comment->content, 'comment');

        $response->assertStatus(201)
            ->assertExactJson(
                [
                    'data' => [
                        'id' => 1,
                        'user_id' => $this->user->id,
                        'user_name' => $this->user->name,
                        'content' => 'comment',
                        'created_at'=> $comment->created_at->toDateTimeString()
                    ]
                ]
            );
    }

    public function test異常系_未ログイン()
    {
        $body = [
            'content' => 'comment'
        ];

        $response = $this->json('post', 'api/rooms/roomName/comments', $body);

        $response->assertStatus(401);

        $existsComment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->exists();
        $this->assertFalse($existsComment);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $body = [
            'content' => 'comment'
        ];

        $this->actingAs($this->user);
        $response = $this->json('post', 'api/rooms/roomName/comments', $body);

        $response->assertStatus(403);

        $existsComment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->exists();
        $this->assertFalse($existsComment);
    }

    public function test異常系_ルーム存在なし()
    {
        $body = [
            'content' => 'comment'
        ];

        $this->actingAs($this->user);
        $response = $this->json('post', 'api/rooms/notExistRoomName/comments', $body);

        $response->assertStatus(404);

        $existsComment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->exists();
        $this->assertFalse($existsComment);
    }

    public function test異常系_ログインユーザがルーム未参加()
    {
        $body = [
            'content' => 'comment'
        ];

        $this->actingAs($this->user);
        $response = $this->json('post', 'api/rooms/notJoinRoomName/comments', $body);

        $response->assertStatus(403);

        $existsComment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->exists();
        $this->assertFalse($existsComment);
    }

    public function test異常系_バリデーションエラー()
    {
        $body = [
            'content' => ''
        ];

        $this->actingAs($this->user);
        $response = $this->json('post', 'api/rooms/roomName/comments', $body);

        $response->assertStatus(422);

        $existsComment = Comment::where('user_id', $this->user->id)
            ->where('room_id', $this->user->joiningRooms[0]->id)
            ->exists();
        $this->assertFalse($existsComment);
    }

    /**
     * @param array $data
     * @param bool $expect
     * @dataProvider dataProviderValidation
     */
    public function testバリデーション(array $data, bool $expect): void
    {
        $request = new StoreRequest();
        $validator = Validator::make(
            $data,
            $request->rules()
        );
        $this->assertEquals(
            $expect,
            $validator->passes()
        );
    }

    public function dataProviderValidation(): array
    {
        return [
            'OK_コメント最小' => [
                ['content' => 'a'],
                true
            ],
            'OK_コメント最大' => [
                ['content' => Str::repeat('a', 255)],
                true
            ],
            'NG_コメント無し' => [
                ['content' => ''],
                false
            ],
            'NG_コメント最大超え' => [
                ['content' => Str::repeat('a', 256)],
                false
            ]
        ];
    }
}
