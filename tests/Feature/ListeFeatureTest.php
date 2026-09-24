<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_list_and_add_a_film(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 550,
            'titre' => 'Fight Club',
        ]);

        $this->actingAs($user)
            ->post(route('listes.store'), [
                'titre' => 'Films à voir',
                'description' => 'Une sélection personnelle',
            ])
            ->assertRedirect(route('dashboard'));

        $liste = $user->listes()->firstOrFail();

        $this->actingAs($user)
            ->post(route('listes.films.store', $liste), [
                'film_id' => $film->id,
            ])
            ->assertRedirect(route('listes.show', $liste));

        $this->assertTrue($liste->films()->whereKey($film)->exists());
    }
}
