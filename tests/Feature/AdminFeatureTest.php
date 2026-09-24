<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_access_admin_page(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $user = User::factory()->create();
        $film = Film::create(['tmdb_id' => 201, 'titre' => 'Film administré']);

        $this->actingAs($user)
            ->get(route('admin.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk()
            ->assertSee($user->email)
            ->assertSee($film->titre);
    }

    public function test_admin_can_promote_a_user(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.promote', $user))
            ->assertRedirect(route('admin.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_is_redirected_to_admin_page_after_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
        $admin->forceFill(['is_admin' => true])->save();

        $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.index'));
    }
}