<?php

namespace Tests\Feature\Room;

use App\Http\Requests\Room\StoreRequest;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test正常系_パスワード無し()
    {
        $body = [
            'name' => 'roomName',
        ];

        $this->actingAs($this->user);
        $response = $this->json('POST', 'api/rooms', $body);

        $response->assertStatus(201)
            ->assertExactJson(
                [
                    "data" => [
                        'id' => 1,
                        'name' => 'roomName',
                        'locked' => false
                    ]
                ]
            );
    }

    public function test正常系_ルーム最大、パスワード有り()
    {
        $name = Str::repeat('a', 255);
        $body = [
            'name' => $name,
            'password' => 'password',
        ];

        $this->actingAs($this->user);
        $response = $this->json('POST', 'api/rooms', $body);

        $response->assertStatus(201)
            ->assertExactJson(
                [
                    "data" => [
                        'id' => 1,
                        'name' => $name,
                        'locked' => true
                    ]
                ]
            );
    }

    public function test異常系_未ログイン()
    {
        $body = [
            'name' => 'roomName',
        ];

        $response = $this->json('POST', 'api/rooms', $body);

        $response->assertStatus(401);
    }

    public function test異常系_email未認証()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $body = [
            'name' => 'roomName',
        ];

        $this->actingAs($this->user);
        $response = $this->json('POST', 'api/rooms', $body);

        $response->assertStatus(403);
    }

    public function test異常系_バリデーションエラー()
    {
        $body = [
            'name' => '',
        ];

        $this->actingAs($this->user);
        $response = $this->json('POST', 'api/rooms', $body);

        $response->assertStatus(422);
    }

    /**
     * @param array $data
     * @param bool $expect
     * @dataProvider dataProviderValidation
     */
    public function testバリデーション(array $data, bool $expect): void
    {
        // 名前重複エラー用
        $this->user->ownRooms()->save(
            Room::factory()->make(['name' => 'duplicate'])
        );

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
            'OK＿名前最小、パスワード無し' => [
                [
                    'name' => 'a'
                ],
                true
            ],
            'OK＿名前最大、パスワード有り最小' => [
                [
                    'name' => Str::repeat('a', 255),
                    'password' => 'a'
                ],
                true
            ],
            'OK＿パスワード有り最大' => [
                [
                    'name' => 'a',
                    'password' => Str::repeat('a', 255)
                ],
                true
            ],
            'NG＿名前必須' => [
                [
                    'name' => ''
                ],
                false
            ],
            'NG＿名前最大超え' => [
                [
                    'name' => Str::repeat('a', 256)
                ],
                false
            ],
            'NG_名前重複' => [
                [
                    'name' => 'duplicate'
                ],
                false
            ],
            'NG_パスワード有り、設定無し' => [
                [
                    'name' => 'name',
                    'password' => ''
                ],
                false
            ],
            'NG_パスワード有り、最大超え' => [
                [
                    'name' => 'name',
                    'password' => Str::repeat('a', 256)
                ],
                false
            ]
        ];
    }
}
