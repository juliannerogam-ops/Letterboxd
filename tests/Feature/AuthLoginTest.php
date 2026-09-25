<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_login_returns_specific_error_message(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'bad@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertSame('Identifiants incorrects.', session('errors')->get('email')[0]);
    }

    public function test_authenticated_user_can_log_out_from_the_profile_button(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_guest_can_register_without_being_redirected_to_the_admin_film_list(): void
    {
        $response = $this->post(route('register'), [
            'email' => 'new-user@example.com',
            'name' => 'Durand',
            'first_name' => 'Camille',
            'pseudo' => 'camille-durand',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('recommendations.genre.create'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new-user@example.com',
            'pseudo' => 'camille-durand',
            'is_admin' => false,
        ]);
    }
}
