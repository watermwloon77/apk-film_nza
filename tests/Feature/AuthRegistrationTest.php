<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_then_log_in(): void
    {
        $attributes = [
            'name' => 'Nabila Zahra Aulia',
            'email' => 'nabila@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->post(route('register.post'), $attributes)
            ->assertRedirect(route('home'));

        $user = User::where('email', $attributes['email'])->firstOrFail();

        $this->assertDatabaseHas('roles', [
            'id' => $user->role_id,
            'name' => 'user',
        ]);
        $this->assertNotNull($user->role_id);
        $this->assertAuthenticatedAs($user);

        Auth::logout();

        $this->post(route('login.post'), [
            'email' => $attributes['email'],
            'password' => $attributes['password'],
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }
}
