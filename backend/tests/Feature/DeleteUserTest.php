<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザを削除できる()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $response = $this->json('DELETE', route('delete-user'));

        $response
            ->assertStatus(204);

        $this->assertNull(Auth::guard('web')->user());

        $this->assertNull(User::find($user->id));
    }

    public function test_ログインしていない場合401()
    {
        $user = User::factory()->create();
        $response = $this->json('DELETE', route('delete-user'));

        $response
            ->assertStatus(401);

        $this->assertNotNull(User::find($user->id));
    }
}
