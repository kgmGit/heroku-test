<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test正常系_ユーザ削除()
    {
        $this->actingAs($this->user);
        $response = $this->json('DELETE', 'user');

        $response->assertStatus(204);

        $this->assertNull(auth()->guard('web')->user());

        $this->assertNull(User::find($this->user->id));
    }

    public function test異常系_未ログイン()
    {
        $response = $this->json('DELETE', 'user');

        $response->assertStatus(401);

        $this->assertNotNull(User::find($this->user->id));
    }
}
