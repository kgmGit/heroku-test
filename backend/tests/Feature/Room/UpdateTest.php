<?php

namespace Tests\Feature\Room;

use App\Http\Requests\Room\UpdateRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTest extends TestCase
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

    public function test正常系_ルーム名変更()
    {
        $body = [
            'name' => 'changeName'
        ];

        $this->actingAs($this->user);
        $response = $this->json('PATCH', 'api/rooms/roomName', $body);

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'id' => 1,
                        'name' => 'changeName'
                    ]
                ]
            );
    }

    public function test異常系_未ログイン()
    {
        $body = [
            'name' => 'changeName',
        ];

        $response = $this->json('PATCH', 'api/rooms/roomName', $body);

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $body = [
            'name' => 'changeName',
        ];

        $this->actingAs($this->user);
        $response = $this->json('PATCH', 'api/rooms/roomName', $body);

        $response->assertStatus(403);
    }

    public function test異常系_ルーム存在なし()
    {
        $body = [
            'name' => 'changeName'
        ];

        $this->actingAs($this->user);
        $response = $this->json('PATCH', 'api/rooms/doesNotExistName', $body);

        $response->assertStatus(404);
    }

    public function test異常系_ログインユーザのルームでない()
    {
        $body = [
            'name' => 'changeName',
        ];

        $this->actingAs($this->user);
        $response = $this->json('PATCH', 'api/rooms/otherRoomName', $body);

        $response->assertStatus(403);
    }

    public function test異常系_バリデーションエラー()
    {
        $body = [
            'name' => '',
        ];

        $this->actingAs($this->user);
        $response = $this->json('PATCH', 'api/rooms/roomName', $body);

        $response->assertStatus(422);
    }

    /**
     * @param array $data
     * @param bool $expect
     * @dataProvider dataProviderValidation
     */
    public function testバリデーション(array $data, bool $expect): void
    {
        $request = new UpdateRequest();
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
            'OK_名前最小' => [
                ['name' => 'a'],
                true
            ],
            'OK_名前最大' => [
                ['name' => Str::repeat('a', 255)],
                true
            ],
            'NG_名前必須' => [
                ['name' => ''],
                false
            ],
            'NG_名前最大超え' => [
                ['name' => Str::repeat('a', 256)],
                false
            ]
        ];
    }
}
