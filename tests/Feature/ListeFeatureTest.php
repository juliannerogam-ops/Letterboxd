<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Liste;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_default_lists(): void
    {
        $user = User::factory()->create();

        Liste::createDefaultsFor($user);

        $this->assertEqualsCanonicalizing(
            [Liste::TYPE_WATCHLIST, Liste::TYPE_FAVORITES, Liste::TYPE_TOP_FIVE],
            $user->listes()->pluck('type')->all(),
        );
    }

    public function test_authenticated_user_can_create_a_list_and_add_a_film(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 550,
            'titre' => 'Fight Club',
        ]);

        $this->actingAs($user)
            ->get(route('listes'))
            ->assertOk()
            ->assertViewIs('listes');

        $this->actingAs($user)
            ->post(route('listes.store'), [
                'titre' => 'Films à voir',
                'description' => 'Une sélection personnelle',
            ])
            ->assertRedirect(route('listes'));

        $liste = $user->listes()->firstOrFail();

        $this->actingAs($user)
            ->post(route('listes.films.store', $liste), [
                'film_id' => $film->id,
            ])
            ->assertRedirect(route('listes.show', $liste));

        $this->assertTrue($liste->films()->whereKey($film)->exists());
    }

    public function test_adding_a_film_at_a_top_five_position_shifts_later_films(): void
    {
        $user = User::factory()->create();
        $topFive = $user->listes()->create([
            'titre' => 'Mon top 5',
            'type' => Liste::TYPE_TOP_FIVE,
        ]);
        $films = collect(range(1, 6))->map(fn (int $tmdbId) => Film::create([
            'tmdb_id' => $tmdbId,
            'titre' => 'Film ' . $tmdbId,
        ]));

        foreach ($films->take(5) as $index => $film) {
            $topFive->films()->attach($film, ['position' => $index + 1]);
        }

        $this->actingAs($user)
            ->post(route('listes.films.store', $topFive), [
                'film_id' => $films[5]->id,
                'position' => 2,
            ])
            ->assertRedirect(route('listes.show', $topFive));

        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[0]->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[5]->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[1]->id,
            'position' => 3,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[2]->id,
            'position' => 4,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[3]->id,
            'position' => 5,
        ]);
        $this->assertDatabaseMissing('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[4]->id,
        ]);
    }
}
