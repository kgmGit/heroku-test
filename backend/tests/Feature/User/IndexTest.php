<?php

namespace Tests\Feature\User;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->joinUsers = User::factory()->count(2)->create();
        $this->notJoinUsers = User::factory()->count(2)->create();

        $this->joinRoom = $this->joinUsers[0]->ownRooms()->save(
            Room::factory()->make()
        );

        $this->joinRoom->members()->saveMany($this->joinUsers);
    }

    public function testルームに紐づくユーザ一覧取得()
    {
        // 想定結果作成
        $expectJson = [
            'data' => [
                [
                    'id' => $this->joinUsers[0]->id,
                    'name' => $this->joinUsers[0]->name,
                    'email' => $this->joinUsers[0]->email,
                    'email_verified_at' => $this->joinUsers[0]->email_verified_at,
                ],
                [
                    'id' => $this->joinUsers[1]->id,
                    'name' => $this->joinUsers[1]->name,
                    'email' => $this->joinUsers[1]->email,
                    'email_verified_at' => $this->joinUsers[1]->email_verified_at,
                ],
            ]
        ];

        // テスト開始
        $this->actingAs($this->joinUsers[0]);
        $response = $this->json('GET', "api/rooms/{$this->joinRoom->name}/members");

        $response->assertStatus(200)
            ->assertExactJson(
                $expectJson
            );
    }

    public function test異常系_未ログイン()
    {
        $response = $this->json('GET', "api/rooms/{$this->joinRoom->name}/members");

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->joinUsers[0]->email_verified_at = null;
        $this->joinUsers[0]->save();

        $this->actingAs($this->joinUsers[0]);
        $response = $this->json('GET', "api/rooms/{$this->joinRoom->name}/members");

        $response->assertStatus(403);
    }

    public function test異常系_ルーム存在なし()
    {
        $this->actingAs($this->joinUsers[0]);
        $response = $this->json('GET', "api/rooms/notExistRoomName/members");

        $response->assertStatus(404);
    }

    public function test異常系_ログインユーザがルーム未参加()
    {
        $this->actingAs($this->notJoinUsers[0]);
        $response = $this->json('GET', "api/rooms/{$this->joinRoom->name}/members");

        $response->assertStatus(403);
    }
}
