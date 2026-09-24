<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_genre_selection_uses_individual_genres_from_film_data(): void
    {
        $user = User::factory()->create();

        Film::create([
            'tmdb_id' => 1,
            'titre' => 'Film aventure romance',
            'genre' => 'Aventure, Romance',
        ]);

        Film::create([
            'tmdb_id' => 2,
            'titre' => 'Film science-fiction',
            'genre' => 'Science-fiction',
        ]);

        $this->actingAs($user)
            ->get(route('recommendations.genre.create'))
            ->assertOk()
            ->assertSee('Aventure')
            ->assertSee('Romance')
            ->assertSee('Science-fiction')
            ->assertDontSee('Aventure, Romance');
    }

    public function test_recommendations_find_a_genre_inside_a_film_genre_list(): void
    {
        $user = User::factory()->create();

        $matchingFilm = Film::create([
            'tmdb_id' => 3,
            'titre' => 'Film aventure',
            'genre' => 'Aventure, Romance',
        ]);

        $otherFilm = Film::create([
            'tmdb_id' => 4,
            'titre' => 'Film science-fiction',
            'genre' => 'Science-fiction',
        ]);

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), ['genre' => 'Aventure'])
            ->assertRedirect(route('dashboard'));

        $this->actingAs($user)
            ->withSession(['preferred_genre' => 'Aventure'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('recommendations', function ($recommendations) use ($matchingFilm, $otherFilm): bool {
                return $recommendations->contains($matchingFilm)
                    && ! $recommendations->contains($otherFilm);
            });
    }

    public function test_dashboard_contains_release_films_for_the_calendar(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 5,
            'titre' => 'Film sorti aujourd’hui',
            'genre' => 'Aventure',
            'date_sortie' => '2026-09-24',
        ]);

        $this->actingAs($user)
            ->withSession(['preferred_genre' => 'Aventure'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('release-calendar')
            ->assertViewHas('releaseFilms', function ($releaseFilms) use ($film): bool {
                return $releaseFilms->contains(fn (array $releaseFilm): bool => $releaseFilm['title'] === $film->titre
                    && $releaseFilm['date'] === '2026-09-24');
            });
    }
}