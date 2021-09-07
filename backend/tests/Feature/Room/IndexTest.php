<?php

namespace Tests\Feature\Room;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user1;
    private User $user2;
    private array $expectJsonUser1;
    private array $expectJsonAll;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1->ownRooms()->saveMany(
            Room::factory()->count(2)->make()
        );
        $this->user2->ownRooms()->saveMany(
            Room::factory()->count(1)->make()
        );


        foreach (Room::all() as $room) {
            $this->expectJsonAll[] = [
                'id' => $room->id,
                'name' => $room->name,
            ];
        }

        foreach ($this->user1->ownRooms as $room) {
            $this->expectJsonUser1[] = [
                'id' => $room->id,
                'name' => $room->name,
            ];
        }
    }

    public function test正常系_クエリ文字列無し()
    {
        $this->actingAs($this->user1);
        $response = $this->json('GET', 'api/rooms');

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => $this->expectJsonAll,
                ]
            );
    }

    public function test正常系_クエリ文字列有り()
    {
        $this->actingAs($this->user1);
        $response = $this->json('GET', 'api/rooms?user-id=' . $this->user1->id);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => $this->expectJsonUser1,
                ]
            );
    }

    public function test異常系_未ログイン()
    {
        $response = $this->json('GET', 'api/rooms');

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->user1->email_verified_at = null;
        $this->user1->save();

        $this->actingAs($this->user1);
        $response = $this->json('GET', 'api/rooms');

        $response->assertStatus(403);
    }

    public function test異常系_別ユーザのルーム取得()
    {
        $this->actingAs($this->user1);
        $response = $this->json('GET', 'api/rooms?user-id=' . $this->user2->id);

        $response->assertStatus(403);
    }
}
