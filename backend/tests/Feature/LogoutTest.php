<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_ログイン済みユーザでログアウトできる()
    {
        $this->actingAs($this->user);
        $response = $this->json('POST', route('logout'));

        $response->assertStatus(200);
        $this->assertGuest();
    }
}
