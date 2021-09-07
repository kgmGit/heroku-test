<?php

namespace Tests\Feature\Room;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JoinTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user->ownRooms()->saveMany(
            [
                Room::factory()->make([
                    'name' => 'roomName'
                ]),
                Room::factory()->make([
                    'name' => 'lockedRoomName',
                    'password' => Hash::make('password')
                ]),
                Room::factory()->make([
                    'name' => 'joinedRoomName'
                ])
            ]
        );
        $this->user->joiningRooms()->attach(
            Room::where('name', 'joinedRoomName')->first()
        );
    }

    public function test正常系_パスワード無し、新規参加()
    {
        $body = [];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/roomName/members', $body);

        $response->assertStatus(201);
        $this->assertTrue(
            $this->user->joiningRooms()->wherePivot(
                'room_id',
                Room::where('name', 'roomName')->first()->id
            )->exists()
        );
    }

    public function test正常系_パスワード無し、参加済み()
    {
        $body = [];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/joinedRoomName/members', $body);

        $response->assertStatus(204);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'joinedRoomName')->first()->id
        )->exists();

        $this->assertTrue($roomBelongsToUser);
    }

    public function test正常系_パスワード有り、新規参加()
    {
        $this->withoutExceptionHandling();
        $body = [
            'password' => 'password'
        ];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/lockedRoomName/members', $body);

        $response->assertStatus(201);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'lockedRoomName')->first()->id
        )->exists();

        $this->assertTrue($roomBelongsToUser);
    }

    public function test異常系_未ログイン()
    {
        $body = [];

        $response = $this->json('PUT', 'api/rooms/roomName/members', $body);

        $response->assertStatus(401);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'roomName')->first()->id
        )->exists();

        $this->assertFalse($roomBelongsToUser);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $body = [];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/roomName/members', $body);

        $response->assertStatus(403);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'roomName')->first()->id
        )->exists();

        $this->assertFalse($roomBelongsToUser);
    }

    public function test異常系_ルーム存在なし()
    {
        $body = [];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/doesNotExistName/members', $body);

        $response->assertStatus(404);
    }

    public function test異常系_パスワード不一致()
    {
        $body = [
            'password' => 'wrongPassword',
        ];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/lockedRoomName/members', $body);

        $response->assertStatus(422);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'lockedRoomName')->first()->id
        )->exists();

        $this->assertFalse($roomBelongsToUser);
    }

    public function test異常系_バリデーションエラー()
    {
        $body = [
            'password' => '',
        ];

        $this->actingAs($this->user);
        $response = $this->json('PUT', 'api/rooms/lockedRoomName/members', $body);

        $response->assertStatus(422);

        $roomBelongsToUser = $this->user->joiningRooms()->wherePivot(
            'room_id',
            Room::where('name', 'lockedRoomName')->first()->id
        )->exists();

        $this->assertFalse($roomBelongsToUser);
    }
}
