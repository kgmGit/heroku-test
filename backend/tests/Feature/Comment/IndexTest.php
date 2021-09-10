<?php

namespace Tests\Feature\Comment;

use App\Http\Requests\Comment\IndexRequest;
use App\Models\Comment;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Carbon $targetTime;
    private Carbon $oldCommentTime;
    private Carbon $newCommentTime;
    private array $expectJsonAll;
    private array $expectJsonTarget;

    protected function setUp(): void
    {
        parent::setUp();

        // テストデータ作成
        $this->user = User::factory()->create();
        $this->user->ownRooms()->saveMany(
            [
                Room::factory()->make(['name' => 'roomName']),
                Room::factory()->make(['name' => 'otherRoomName'])
            ]
        );
        $otherUser = User::factory()->create();
        $otherUser->ownRooms()->save(
            Room::factory()->make(['name' => 'otherUserRoomName'])
        );

        $this->targetTime = Carbon::now();
        $this->oldCommentTime = $this->targetTime->copy()->subSeconds();
        $this->newCommentTime = $this->targetTime->copy()->addSeconds();

        /** @var Room @targetRoom */
        $targetRoom = Room::find(1);
        /** @var Room @otherRoom */
        $otherRoom = Room::find(2);

        $targetRoom->comments()->saveMany(
            [
                Comment::factory()->make(['user_id' => $this->user->id, 'created_at' => $this->oldCommentTime->toDateTimeString()]),
                Comment::factory()->make(['user_id' => $this->user->id, 'created_at' => $this->newCommentTime->toDateTimeString()]),
            ]
        );
        $otherRoom->comments()->saveMany(
            [
                Comment::factory()->make(['user_id' => $this->user->id, 'created_at' => $this->oldCommentTime->toDateTimeString()]),
                Comment::factory()->make(['user_id' => $this->user->id, 'created_at' => $this->newCommentTime->toDateTimeString()]),
            ]
        );
        // 想定結果作成
        $this->expectJsonAll = [
            'data' => [
                [
                    'id' => $targetRoom->comments[0]->id,
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'content' => $targetRoom->comments[0]->content,
                    'created_at' => $targetRoom->comments[0]->created_at->toDateTimeString()
                ],
                [
                    'id' => $targetRoom->comments[1]->id,
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'content' => $targetRoom->comments[1]->content,
                    'created_at' => $targetRoom->comments[1]->created_at->toDateTimeString()
                ],
            ]
        ];
        $this->expectJsonTarget = [
            'data' => [
                [
                    'id' => $targetRoom->comments[1]->id,
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'content' => $targetRoom->comments[1]->content,
                    'created_at' => $targetRoom->comments[1]->created_at->toDateTimeString()
                ],
            ]
        ];
    }

    public function test正常系_全コメント取得()
    {
        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/roomName/comments');

        $response->assertStatus(200)
            ->assertExactJson($this->expectJsonAll);
    }

    public function test正常系_指定日時以降のコメント取得()
    {
        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/roomName/comments?time=' . $this->targetTime->toDateTimeString());

        $response->assertStatus(200)
            ->assertExactJson($this->expectJsonTarget);
    }

    public function test異常系_未ログイン()
    {
        $response = $this->json('GET', 'api/rooms/roomName/comments');

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/roomName/comments');

        $response->assertStatus(403);
    }

    public function test異常系_ルーム存在なし()
    {
        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/doesNotExistName/comments');

        $response->assertStatus(404);
    }

    public function test異常系_ログインユーザがルームに所属していない()
    {
        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/otherUserRoomName/comments');

        $response->assertStatus(403);
    }

    public function test異常系_バリデーションエラー()
    {
        $this->actingAs($this->user);
        $response = $this->json('GET', 'api/rooms/roomName/comments?time=a');

        $response->assertStatus(422);
    }

    /**
     * @param array $data
     * @param bool $expect
     * @dataProvider dataProviderValidation
     */
    public function testバリデーション(array $data, bool $expect): void
    {
        $request = new IndexRequest();
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
            'OK_指定日時設定無し' => [[] , true],
            'OK_指定日時設定有り' => [['time' => '2021-01-01 00:00:00'], true],
            'NG_指定日時が日付でない' => [['time' => '2021-01-01 a'], false],
        ];
    }
}
