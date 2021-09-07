<?php

namespace Tests\Feature\Room;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->ownRooms()->save(
            Room::factory()->make([
                'name' => 'roomName'
            ])
        );

        $this->otherUser = User::factory()->create();
        $this->otherUser->ownRooms()->save(
            Room::factory()->make([
                'name' => 'otherRoomName'
            ])
        );
    }

    public function test正常系_ルーム削除()
    {
        $this->actingAs($this->user);
        $response = $this->json('DELETE', 'api/rooms/roomName');

        $response->assertStatus(204);
    }

    public function test異常系_未ログイン()
    {
        $response = $this->json('DELETE', 'api/rooms/roomName');

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $this->actingAs($this->user);
        $response = $this->json('DELETE', 'api/rooms/roomName');

        $response->assertStatus(403);
    }

    public function test異常系_ルーム存在なし()
    {
        $this->actingAs($this->user);
        $response = $this->json('DELETE', 'api/rooms/doesNotExistName');

        $response->assertStatus(404);
    }

    public function test異常系_ログインユーザのルームでない()
    {
        $this->actingAs($this->user);
        $response = $this->json('DELETE', 'api/rooms/otherRoomName');

        $response->assertStatus(403);
    }
}
