<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_登録済みのユーザを認証して返却する()
    {
        // $this->withoutExceptionHandling();
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ],
            ]);
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_バリデーションエラーの場合422()
    {
        $response = $this->json('POST', route('login'), [
            'email' => '',
            'password' => '',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['email は必須です'],
                    'password' => ['password は必須です'],
                ]
            ]);
        $this->assertGuest();

        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => '',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['password は必須です'],
                ]
            ]);
        $this->assertGuest();

        $response = $this->json('POST', route('login'), [
            'email' => '',
            'password' => 'password',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['email は必須です'],
                ]
            ]);
        $this->assertGuest();
    }

    public function test_ユーザが存在しない場合404()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'XXX',
        ]);
        $response->assertStatus(404);
        $this->assertGuest();

        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email . 'XXX',
            'password' => 'password',
        ]);
        $response->assertStatus(404);
        $this->assertGuest();
    }
}
